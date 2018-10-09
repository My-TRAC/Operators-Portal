package controllers

import (
	"bitbucket.org/sparsitytechnologies/webapp-joan/models"
	"bitbucket.org/sparsitytechnologies/webapp-joan/modules/utils"

	"github.com/astaxie/beego"
)

type SamplesController struct {
	//beego.Controller
	BaseController
}

func (c *SamplesController) Prepare() {
	// read flash messages
	_ = beego.ReadFromRequest(&c.Controller)
	c.BaseController.Prepare()

	if c.GetSession("msToRefresh") == nil {
		msToRefresh := utils.GetMsToRefresh()

		if msToRefresh == "" {
			c.SetSession("msToRefresh", "30000")
		} else {
			c.SetSession("msToRefresh", msToRefresh)
		}
	}
}

func (c *SamplesController) Get() {

	list, err := models.FindSamples()

	if err != nil {
		flash := beego.NewFlash()
		flash.Error(err.Error())
		flash.Store(&c.Controller)
	} else {
		c.Data["objs"] = list
		c.Data["msToRefresh"] = c.GetSession("msToRefresh")
	}

	c.TplName = "samples/list.tpl"
}
