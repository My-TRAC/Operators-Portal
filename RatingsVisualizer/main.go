package main

import (
	"os"
	"strconv"

	"bitbucket.org/sparsitytechnologies/webapp-joan/models"
	_ "bitbucket.org/sparsitytechnologies/webapp-joan/routers"

	//"github.com/asaskevich/govalidator"
	"github.com/astaxie/beego"
)

func main() {

	models.DB = models.CreateDB()

	models.DB.LogMode(true)

	models.LoadlibDB()

	//govalidator.SetFieldsRequiredByDefault(true)
	beego.BConfig.Listen.HTTPPort, _ = strconv.Atoi(os.Getenv("PORT"))

	beego.Run()
}
