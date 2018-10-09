package models

import (
	"bytes"
	"fmt"
	"math/rand"
	"os"
	"strings"
	"time"

	"bitbucket.org/sparsitytechnologies/webapp-joan/modules/utils"
	"github.com/astaxie/beego"
	"github.com/go-sql-driver/mysql"
	"github.com/jinzhu/gorm"
)

var DB *gorm.DB

var err error

func LoadlibDB() {

	DB.AutoMigrate(&Sample{})

	samplesStartup()
}

func CreateDB() *gorm.DB {
	var mysqlConnect bytes.Buffer

	mysqluser := beego.AppConfig.String("mysqluser")
	fmt.Println("mysqluser:" + mysqluser)
	mysqlConnect.WriteString(mysqluser)

	mysqlConnect.WriteString(":")

	var mysqlpass string
	mysqlpass = os.Getenv("MYSQL_PASS")
	if mysqlpass == "" {
		mysqlpass = beego.AppConfig.String("mysqlpass")
	}
	mysqlConnect.WriteString(mysqlpass)

	mysqlConnect.WriteString("@tcp(")

	mysqlhost := os.Getenv("MYSQL_HOST")
	if mysqlhost == "" {
		mysqlhost = beego.AppConfig.String("mysqlhost")
	}
	fmt.Println("mysqlhost:" + mysqlhost)

	mysqlConnect.WriteString(mysqlhost)

	mysqlport := beego.AppConfig.String("mysqlport")
	if mysqlport == "" {
		mysqlport = "3306"
	}
	fmt.Println("mysqlport:" + mysqlport)
	mysqlConnect.WriteString(":" + mysqlport + ")/")

	mysqldb := beego.AppConfig.String("mysqldb")
	fmt.Println("mysqldb:" + mysqldb)

	mysqlConnect.WriteString(mysqldb)
	mysqlConnect.WriteString("?charset=utf8&parseTime=True&loc=Local")

	var db *gorm.DB
	db, err = gorm.Open("mysql", mysqlConnect.String())

	if err != nil {
		fmt.Println("Failed to connect database " + err.Error())

		fmt.Println("Trying to create a database: " + mysqldb)

		if mErr, ok := err.(*mysql.MySQLError); ok && mErr.Number == 1049 {
			err = create(mysqluser, mysqlpass, mysqlhost, mysqlport, mysqldb)
			if err == nil {
				db, err = gorm.Open("mysql", mysqlConnect.String())
				if err != nil {
					panic(err)
				}
			}
		}
	}

	return db
}

func create(mysqluser, mysqlpass, mysqlhost, mysqlport, mysqldb string) error {
	var err error

	var mysqlConnect []string

	mysqlConnect = append(mysqlConnect, mysqluser, ":", mysqlpass)
	mysqlConnect = append(mysqlConnect, "@tcp(", mysqlhost, ":", mysqlport, ")/?charset=utf8&parseTime=True&loc=Local")

	db, err := gorm.Open("mysql", strings.Join(mysqlConnect, ""))

	if err == nil {
		fmt.Println("CREATE DATABASE " + mysqldb)
		err = db.Exec("CREATE DATABASE " + mysqldb).Error
	}

	return err

}

func samplesStartup() {
	createFakeSamples := false

	list, err := FindSamples()
	if err == nil {
		if len(list) == 0 {
			createFakeSamples = true
		}
	}

	if createFakeSamples {
		rand.Seed(time.Now().UnixNano())

		i := 0

		for {
			if i == utils.GetNumRandomSamples() {
				break
			}
			i++

			sample := Sample{
				Code:        RandStringRunes(6),
				Description: RandStringRunes(20),
				Value:       randomFloat64(0, 100),
			}

			sample.Insert()
		}
	}
}

var letterRunes = []rune("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ")

func RandStringRunes(n int) string {
	b := make([]rune, n)
	for i := range b {
		b[i] = letterRunes[rand.Intn(len(letterRunes))]
	}
	return string(b)
}

func randomFloat64(min, max float64) float64 {
	return rand.Float64()*(max-min) + min
}
