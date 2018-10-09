package routers

import (
	"bitbucket.org/sparsitytechnologies/webapp-joan/controllers"
	"github.com/astaxie/beego"
)

func init() {
	beego.Router("/", &controllers.SamplesController{})
}
