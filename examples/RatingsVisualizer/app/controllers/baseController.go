package controllers

import (
	"strings"

	"github.com/astaxie/beego"
	"github.com/beego/i18n"
)

var langTypes []string // Languages that are supported.
// baseController represents base router for all other app routers.
// It implemented some methods for the same implementation;
// thus, it will be embedded into other routers.

type BaseController struct {
	// Embedding: "Inherit" beego.Controller
	beego.Controller
	i18n.Locale // For i18n usage when process data and render template.
}

func init() {
	beego.AddFuncMap("i18n", i18n.Tr)

	// Initialize language type list.
	langTypes = strings.Split(beego.AppConfig.String("lang_types"), "|")

	// Load locale files according to language types.
	for _, lang := range langTypes {
		beego.Trace("Loading language: " + lang)
		if err := i18n.SetMessage(lang, "conf/"+"locale_"+lang+".ini"); err != nil {
			beego.Error("Fail to set message file:", err)
			return
		}
	}
}

func (this *BaseController) Prepare() {

	// 1. Check URL arguments.
	lang := this.Input().Get("lang")

	// 2. Get language information from cookies.
	if len(lang) == 0 {
		lang = this.Ctx.GetCookie("lang")
	}

	// Check again in case someone modify on purpose.
	if !i18n.IsExist(lang) {
		lang = ""
	}

	// 3. Get language information from 'Accept-Language'.
	if len(lang) == 0 {
		al := this.Ctx.Request.Header.Get("Accept-Language")

		s := strings.Split(al, ",")
		s = strings.Split(s[0], ";")
		al = strings.TrimSpace(s[0])
		if i18n.IsExist(al) {
			lang = al
		}
	}

	// 4. Default language is English.
	if len(lang) == 0 {
		lang = "en-US"
	}

	this.Data["Lang"] = lang
	this.Lang = lang

	// Overwrite beego.Controller.Layout (string)
	//this.Layout = "layoutAuth.tpl"
}
