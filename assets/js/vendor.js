"use strict";

/*
jQuery Parallax 1.1.3
Author: Ian Lunn
Plugin URL: http://www.ianlunn.co.uk/plugins/jquery-parallax/

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html
*/
!function (n) {
  var t = n(window),
    e = t.height();
  t.resize(function () {
    e = t.height();
  }), n.fn.parallax = function (o, r, i) {
    function u() {
      var i = t.scrollTop();
      l.each(function (t, u) {
        var l = n(u),
          f = l.offset().top,
          s = a(l);
        i > f + s || f > i + e || l.css("backgroundPosition", o + " " + Math.round((l.data("firstTop") - i) * r) + "px");
      });
    }
    var a,
      l = n(this);
    l.each(function (t, e) {
      $element = n(e), $element.data("firstTop", $element.offset().top);
    }), a = i ? function (n) {
      return n.outerHeight(!0);
    } : function (n) {
      return n.height();
    }, (arguments.length < 1 || null === o) && (o = "50%"), (arguments.length < 2 || null === r) && (r = .1), (arguments.length < 3 || null === i) && (i = !0), t.bind("scroll", u).resize(u), u();
  };
}(jQuery);
"use strict";

function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
/*! SmartMenus jQuery Plugin - v1.1.0 - September 17, 2017
 * http://www.smartmenus.org/
 * Copyright Vasil Dinkov, Vadikom Web Ltd. http://vadikom.com; Licensed MIT */
(function (t) {
  "function" == typeof define && define.amd ? define(["jquery"], t) : "object" == (typeof module === "undefined" ? "undefined" : _typeof(module)) && "object" == _typeof(module.exports) ? module.exports = t(require("jquery")) : t(jQuery);
})(function ($) {
  function initMouseDetection(t) {
    var e = ".smartmenus_mouse";
    if (mouseDetectionEnabled || t) mouseDetectionEnabled && t && ($(document).off(e), mouseDetectionEnabled = !1);else {
      var i = !0,
        s = null,
        o = {
          mousemove: function mousemove(t) {
            var e = {
              x: t.pageX,
              y: t.pageY,
              timeStamp: new Date().getTime()
            };
            if (s) {
              var o = Math.abs(s.x - e.x),
                a = Math.abs(s.y - e.y);
              if ((o > 0 || a > 0) && 2 >= o && 2 >= a && 300 >= e.timeStamp - s.timeStamp && (mouse = !0, i)) {
                var n = $(t.target).closest("a");
                n.is("a") && $.each(menuTrees, function () {
                  return $.contains(this.$root[0], n[0]) ? (this.itemEnter({
                    currentTarget: n[0]
                  }), !1) : void 0;
                }), i = !1;
              }
            }
            s = e;
          }
        };
      o[touchEvents ? "touchstart" : "pointerover pointermove pointerout MSPointerOver MSPointerMove MSPointerOut"] = function (t) {
        isTouchEvent(t.originalEvent) && (mouse = !1);
      }, $(document).on(getEventsNS(o, e)), mouseDetectionEnabled = !0;
    }
  }
  function isTouchEvent(t) {
    return !/^(4|mouse)$/.test(t.pointerType);
  }
  function getEventsNS(t, e) {
    e || (e = "");
    var i = {};
    for (var s in t) i[s.split(" ").join(e + " ") + e] = t[s];
    return i;
  }
  var menuTrees = [],
    mouse = !1,
    touchEvents = "ontouchstart" in window,
    mouseDetectionEnabled = !1,
    requestAnimationFrame = window.requestAnimationFrame || function (t) {
      return setTimeout(t, 1e3 / 60);
    },
    cancelAnimationFrame = window.cancelAnimationFrame || function (t) {
      clearTimeout(t);
    },
    canAnimate = !!$.fn.animate;
  return $.SmartMenus = function (t, e) {
    this.$root = $(t), this.opts = e, this.rootId = "", this.accessIdPrefix = "", this.$subArrow = null, this.activatedItems = [], this.visibleSubMenus = [], this.showTimeout = 0, this.hideTimeout = 0, this.scrollTimeout = 0, this.clickActivated = !1, this.focusActivated = !1, this.zIndexInc = 0, this.idInc = 0, this.$firstLink = null, this.$firstSub = null, this.disabled = !1, this.$disableOverlay = null, this.$touchScrollingSub = null, this.cssTransforms3d = "perspective" in t.style || "webkitPerspective" in t.style, this.wasCollapsible = !1, this.init();
  }, $.extend($.SmartMenus, {
    hideAll: function hideAll() {
      $.each(menuTrees, function () {
        this.menuHideAll();
      });
    },
    destroy: function destroy() {
      for (; menuTrees.length;) menuTrees[0].destroy();
      initMouseDetection(!0);
    },
    prototype: {
      init: function init(t) {
        var e = this;
        if (!t) {
          menuTrees.push(this), this.rootId = (new Date().getTime() + Math.random() + "").replace(/\D/g, ""), this.accessIdPrefix = "sm-" + this.rootId + "-", this.$root.hasClass("sm-rtl") && (this.opts.rightToLeftSubMenus = !0);
          var i = ".smartmenus";
          this.$root.data("smartmenus", this).attr("data-smartmenus-id", this.rootId).dataSM("level", 1).on(getEventsNS({
            "mouseover focusin": $.proxy(this.rootOver, this),
            "mouseout focusout": $.proxy(this.rootOut, this),
            keydown: $.proxy(this.rootKeyDown, this)
          }, i)).on(getEventsNS({
            mouseenter: $.proxy(this.itemEnter, this),
            mouseleave: $.proxy(this.itemLeave, this),
            mousedown: $.proxy(this.itemDown, this),
            focus: $.proxy(this.itemFocus, this),
            blur: $.proxy(this.itemBlur, this),
            click: $.proxy(this.itemClick, this)
          }, i), "a"), i += this.rootId, this.opts.hideOnClick && $(document).on(getEventsNS({
            touchstart: $.proxy(this.docTouchStart, this),
            touchmove: $.proxy(this.docTouchMove, this),
            touchend: $.proxy(this.docTouchEnd, this),
            click: $.proxy(this.docClick, this)
          }, i)), $(window).on(getEventsNS({
            "resize orientationchange": $.proxy(this.winResize, this)
          }, i)), this.opts.subIndicators && (this.$subArrow = $("<span/>").addClass("sub-arrow"), this.opts.subIndicatorsText && this.$subArrow.html(this.opts.subIndicatorsText)), initMouseDetection();
        }
        if (this.$firstSub = this.$root.find("ul").each(function () {
          e.menuInit($(this));
        }).eq(0), this.$firstLink = this.$root.find("a").eq(0), this.opts.markCurrentItem) {
          var s = /(index|default)\.[^#\?\/]*/i,
            o = /#.*/,
            a = window.location.href.replace(s, ""),
            n = a.replace(o, "");
          this.$root.find("a").each(function () {
            var t = this.href.replace(s, ""),
              i = $(this);
            (t == a || t == n) && (i.addClass("current"), e.opts.markCurrentTree && i.parentsUntil("[data-smartmenus-id]", "ul").each(function () {
              $(this).dataSM("parent-a").addClass("current");
            }));
          });
        }
        this.wasCollapsible = this.isCollapsible();
      },
      destroy: function destroy(t) {
        if (!t) {
          var e = ".smartmenus";
          this.$root.removeData("smartmenus").removeAttr("data-smartmenus-id").removeDataSM("level").off(e), e += this.rootId, $(document).off(e), $(window).off(e), this.opts.subIndicators && (this.$subArrow = null);
        }
        this.menuHideAll();
        var i = this;
        this.$root.find("ul").each(function () {
          var t = $(this);
          t.dataSM("scroll-arrows") && t.dataSM("scroll-arrows").remove(), t.dataSM("shown-before") && ((i.opts.subMenusMinWidth || i.opts.subMenusMaxWidth) && t.css({
            width: "",
            minWidth: "",
            maxWidth: ""
          }).removeClass("sm-nowrap"), t.dataSM("scroll-arrows") && t.dataSM("scroll-arrows").remove(), t.css({
            zIndex: "",
            top: "",
            left: "",
            marginLeft: "",
            marginTop: "",
            display: ""
          })), 0 == (t.attr("id") || "").indexOf(i.accessIdPrefix) && t.removeAttr("id");
        }).removeDataSM("in-mega").removeDataSM("shown-before").removeDataSM("scroll-arrows").removeDataSM("parent-a").removeDataSM("level").removeDataSM("beforefirstshowfired").removeAttr("role").removeAttr("aria-hidden").removeAttr("aria-labelledby").removeAttr("aria-expanded"), this.$root.find("a.has-submenu").each(function () {
          var t = $(this);
          0 == t.attr("id").indexOf(i.accessIdPrefix) && t.removeAttr("id");
        }).removeClass("has-submenu").removeDataSM("sub").removeAttr("aria-haspopup").removeAttr("aria-controls").removeAttr("aria-expanded").closest("li").removeDataSM("sub"), this.opts.subIndicators && this.$root.find("span.sub-arrow").remove(), this.opts.markCurrentItem && this.$root.find("a.current").removeClass("current"), t || (this.$root = null, this.$firstLink = null, this.$firstSub = null, this.$disableOverlay && (this.$disableOverlay.remove(), this.$disableOverlay = null), menuTrees.splice($.inArray(this, menuTrees), 1));
      },
      disable: function disable(t) {
        if (!this.disabled) {
          if (this.menuHideAll(), !t && !this.opts.isPopup && this.$root.is(":visible")) {
            var e = this.$root.offset();
            this.$disableOverlay = $('<div class="sm-jquery-disable-overlay"/>').css({
              position: "absolute",
              top: e.top,
              left: e.left,
              width: this.$root.outerWidth(),
              height: this.$root.outerHeight(),
              zIndex: this.getStartZIndex(!0),
              opacity: 0
            }).appendTo(document.body);
          }
          this.disabled = !0;
        }
      },
      docClick: function docClick(t) {
        return this.$touchScrollingSub ? (this.$touchScrollingSub = null, void 0) : ((this.visibleSubMenus.length && !$.contains(this.$root[0], t.target) || $(t.target).closest("a").length) && this.menuHideAll(), void 0);
      },
      docTouchEnd: function docTouchEnd() {
        if (this.lastTouch) {
          if (!(!this.visibleSubMenus.length || void 0 !== this.lastTouch.x2 && this.lastTouch.x1 != this.lastTouch.x2 || void 0 !== this.lastTouch.y2 && this.lastTouch.y1 != this.lastTouch.y2 || this.lastTouch.target && $.contains(this.$root[0], this.lastTouch.target))) {
            this.hideTimeout && (clearTimeout(this.hideTimeout), this.hideTimeout = 0);
            var t = this;
            this.hideTimeout = setTimeout(function () {
              t.menuHideAll();
            }, 350);
          }
          this.lastTouch = null;
        }
      },
      docTouchMove: function docTouchMove(t) {
        if (this.lastTouch) {
          var e = t.originalEvent.touches[0];
          this.lastTouch.x2 = e.pageX, this.lastTouch.y2 = e.pageY;
        }
      },
      docTouchStart: function docTouchStart(t) {
        var e = t.originalEvent.touches[0];
        this.lastTouch = {
          x1: e.pageX,
          y1: e.pageY,
          target: e.target
        };
      },
      enable: function enable() {
        this.disabled && (this.$disableOverlay && (this.$disableOverlay.remove(), this.$disableOverlay = null), this.disabled = !1);
      },
      getClosestMenu: function getClosestMenu(t) {
        for (var e = $(t).closest("ul"); e.dataSM("in-mega");) e = e.parent().closest("ul");
        return e[0] || null;
      },
      getHeight: function getHeight(t) {
        return this.getOffset(t, !0);
      },
      getOffset: function getOffset(t, e) {
        var i;
        "none" == t.css("display") && (i = {
          position: t[0].style.position,
          visibility: t[0].style.visibility
        }, t.css({
          position: "absolute",
          visibility: "hidden"
        }).show());
        var s = t[0].getBoundingClientRect && t[0].getBoundingClientRect(),
          o = s && (e ? s.height || s.bottom - s.top : s.width || s.right - s.left);
        return o || 0 === o || (o = e ? t[0].offsetHeight : t[0].offsetWidth), i && t.hide().css(i), o;
      },
      getStartZIndex: function getStartZIndex(t) {
        var e = parseInt(this[t ? "$root" : "$firstSub"].css("z-index"));
        return !t && isNaN(e) && (e = parseInt(this.$root.css("z-index"))), isNaN(e) ? 1 : e;
      },
      getTouchPoint: function getTouchPoint(t) {
        return t.touches && t.touches[0] || t.changedTouches && t.changedTouches[0] || t;
      },
      getViewport: function getViewport(t) {
        var e = t ? "Height" : "Width",
          i = document.documentElement["client" + e],
          s = window["inner" + e];
        return s && (i = Math.min(i, s)), i;
      },
      getViewportHeight: function getViewportHeight() {
        return this.getViewport(!0);
      },
      getViewportWidth: function getViewportWidth() {
        return this.getViewport();
      },
      getWidth: function getWidth(t) {
        return this.getOffset(t);
      },
      handleEvents: function handleEvents() {
        return !this.disabled && this.isCSSOn();
      },
      handleItemEvents: function handleItemEvents(t) {
        return this.handleEvents() && !this.isLinkInMegaMenu(t);
      },
      isCollapsible: function isCollapsible() {
        return "static" == this.$firstSub.css("position");
      },
      isCSSOn: function isCSSOn() {
        return "inline" != this.$firstLink.css("display");
      },
      isFixed: function isFixed() {
        var t = "fixed" == this.$root.css("position");
        return t || this.$root.parentsUntil("body").each(function () {
          return "fixed" == $(this).css("position") ? (t = !0, !1) : void 0;
        }), t;
      },
      isLinkInMegaMenu: function isLinkInMegaMenu(t) {
        return $(this.getClosestMenu(t[0])).hasClass("mega-menu");
      },
      isTouchMode: function isTouchMode() {
        return !mouse || this.opts.noMouseOver || this.isCollapsible();
      },
      itemActivate: function itemActivate(t, e) {
        var i = t.closest("ul"),
          s = i.dataSM("level");
        if (s > 1 && (!this.activatedItems[s - 2] || this.activatedItems[s - 2][0] != i.dataSM("parent-a")[0])) {
          var o = this;
          $(i.parentsUntil("[data-smartmenus-id]", "ul").get().reverse()).add(i).each(function () {
            o.itemActivate($(this).dataSM("parent-a"));
          });
        }
        if ((!this.isCollapsible() || e) && this.menuHideSubMenus(this.activatedItems[s - 1] && this.activatedItems[s - 1][0] == t[0] ? s : s - 1), this.activatedItems[s - 1] = t, this.$root.triggerHandler("activate.smapi", t[0]) !== !1) {
          var a = t.dataSM("sub");
          a && (this.isTouchMode() || !this.opts.showOnClick || this.clickActivated) && this.menuShow(a);
        }
      },
      itemBlur: function itemBlur(t) {
        var e = $(t.currentTarget);
        this.handleItemEvents(e) && this.$root.triggerHandler("blur.smapi", e[0]);
      },
      itemClick: function itemClick(t) {
        var e = $(t.currentTarget);
        if (this.handleItemEvents(e)) {
          if (this.$touchScrollingSub && this.$touchScrollingSub[0] == e.closest("ul")[0]) return this.$touchScrollingSub = null, t.stopPropagation(), !1;
          if (this.$root.triggerHandler("click.smapi", e[0]) === !1) return !1;
          var i = $(t.target).is(".sub-arrow"),
            s = e.dataSM("sub"),
            o = s ? 2 == s.dataSM("level") : !1,
            a = this.isCollapsible(),
            n = /toggle$/.test(this.opts.collapsibleBehavior),
            r = /link$/.test(this.opts.collapsibleBehavior),
            h = /^accordion/.test(this.opts.collapsibleBehavior);
          if (s && !s.is(":visible")) {
            if ((!r || !a || i) && (this.opts.showOnClick && o && (this.clickActivated = !0), this.itemActivate(e, h), s.is(":visible"))) return this.focusActivated = !0, !1;
          } else if (a && (n || i)) return this.itemActivate(e, h), this.menuHide(s), n && (this.focusActivated = !1), !1;
          return this.opts.showOnClick && o || e.hasClass("disabled") || this.$root.triggerHandler("select.smapi", e[0]) === !1 ? !1 : void 0;
        }
      },
      itemDown: function itemDown(t) {
        var e = $(t.currentTarget);
        this.handleItemEvents(e) && e.dataSM("mousedown", !0);
      },
      itemEnter: function itemEnter(t) {
        var e = $(t.currentTarget);
        if (this.handleItemEvents(e)) {
          if (!this.isTouchMode()) {
            this.showTimeout && (clearTimeout(this.showTimeout), this.showTimeout = 0);
            var i = this;
            this.showTimeout = setTimeout(function () {
              i.itemActivate(e);
            }, this.opts.showOnClick && 1 == e.closest("ul").dataSM("level") ? 1 : this.opts.showTimeout);
          }
          this.$root.triggerHandler("mouseenter.smapi", e[0]);
        }
      },
      itemFocus: function itemFocus(t) {
        var e = $(t.currentTarget);
        this.handleItemEvents(e) && (!this.focusActivated || this.isTouchMode() && e.dataSM("mousedown") || this.activatedItems.length && this.activatedItems[this.activatedItems.length - 1][0] == e[0] || this.itemActivate(e, !0), this.$root.triggerHandler("focus.smapi", e[0]));
      },
      itemLeave: function itemLeave(t) {
        var e = $(t.currentTarget);
        this.handleItemEvents(e) && (this.isTouchMode() || (e[0].blur(), this.showTimeout && (clearTimeout(this.showTimeout), this.showTimeout = 0)), e.removeDataSM("mousedown"), this.$root.triggerHandler("mouseleave.smapi", e[0]));
      },
      menuHide: function menuHide(t) {
        if (this.$root.triggerHandler("beforehide.smapi", t[0]) !== !1 && (canAnimate && t.stop(!0, !0), "none" != t.css("display"))) {
          var e = function e() {
            t.css("z-index", "");
          };
          this.isCollapsible() ? canAnimate && this.opts.collapsibleHideFunction ? this.opts.collapsibleHideFunction.call(this, t, e) : t.hide(this.opts.collapsibleHideDuration, e) : canAnimate && this.opts.hideFunction ? this.opts.hideFunction.call(this, t, e) : t.hide(this.opts.hideDuration, e), t.dataSM("scroll") && (this.menuScrollStop(t), t.css({
            "touch-action": "",
            "-ms-touch-action": "",
            "-webkit-transform": "",
            transform: ""
          }).off(".smartmenus_scroll").removeDataSM("scroll").dataSM("scroll-arrows").hide()), t.dataSM("parent-a").removeClass("highlighted").attr("aria-expanded", "false"), t.attr({
            "aria-expanded": "false",
            "aria-hidden": "true"
          });
          var i = t.dataSM("level");
          this.activatedItems.splice(i - 1, 1), this.visibleSubMenus.splice($.inArray(t, this.visibleSubMenus), 1), this.$root.triggerHandler("hide.smapi", t[0]);
        }
      },
      menuHideAll: function menuHideAll() {
        this.showTimeout && (clearTimeout(this.showTimeout), this.showTimeout = 0);
        for (var t = this.opts.isPopup ? 1 : 0, e = this.visibleSubMenus.length - 1; e >= t; e--) this.menuHide(this.visibleSubMenus[e]);
        this.opts.isPopup && (canAnimate && this.$root.stop(!0, !0), this.$root.is(":visible") && (canAnimate && this.opts.hideFunction ? this.opts.hideFunction.call(this, this.$root) : this.$root.hide(this.opts.hideDuration))), this.activatedItems = [], this.visibleSubMenus = [], this.clickActivated = !1, this.focusActivated = !1, this.zIndexInc = 0, this.$root.triggerHandler("hideAll.smapi");
      },
      menuHideSubMenus: function menuHideSubMenus(t) {
        for (var e = this.activatedItems.length - 1; e >= t; e--) {
          var i = this.activatedItems[e].dataSM("sub");
          i && this.menuHide(i);
        }
      },
      menuInit: function menuInit(t) {
        if (!t.dataSM("in-mega")) {
          t.hasClass("mega-menu") && t.find("ul").dataSM("in-mega", !0);
          for (var e = 2, i = t[0]; (i = i.parentNode.parentNode) != this.$root[0];) e++;
          var s = t.prevAll("a").eq(-1);
          s.length || (s = t.prevAll().find("a").eq(-1)), s.addClass("has-submenu").dataSM("sub", t), t.dataSM("parent-a", s).dataSM("level", e).parent().dataSM("sub", t);
          var o = s.attr("id") || this.accessIdPrefix + ++this.idInc,
            a = t.attr("id") || this.accessIdPrefix + ++this.idInc;
          s.attr({
            id: o,
            "aria-haspopup": "true",
            "aria-controls": a,
            "aria-expanded": "false"
          }), t.attr({
            id: a,
            role: "group",
            "aria-hidden": "true",
            "aria-labelledby": o,
            "aria-expanded": "false"
          }), this.opts.subIndicators && s[this.opts.subIndicatorsPos](this.$subArrow.clone());
        }
      },
      menuPosition: function menuPosition(t) {
        var e,
          i,
          s = t.dataSM("parent-a"),
          o = s.closest("li"),
          a = o.parent(),
          n = t.dataSM("level"),
          r = this.getWidth(t),
          h = this.getHeight(t),
          u = s.offset(),
          l = u.left,
          c = u.top,
          d = this.getWidth(s),
          m = this.getHeight(s),
          p = $(window),
          f = p.scrollLeft(),
          v = p.scrollTop(),
          b = this.getViewportWidth(),
          S = this.getViewportHeight(),
          g = a.parent().is("[data-sm-horizontal-sub]") || 2 == n && !a.hasClass("sm-vertical"),
          M = this.opts.rightToLeftSubMenus && !o.is("[data-sm-reverse]") || !this.opts.rightToLeftSubMenus && o.is("[data-sm-reverse]"),
          w = 2 == n ? this.opts.mainMenuSubOffsetX : this.opts.subMenusSubOffsetX,
          T = 2 == n ? this.opts.mainMenuSubOffsetY : this.opts.subMenusSubOffsetY;
        if (g ? (e = M ? d - r - w : w, i = this.opts.bottomToTopSubMenus ? -h - T : m + T) : (e = M ? w - r : d - w, i = this.opts.bottomToTopSubMenus ? m - T - h : T), this.opts.keepInViewport) {
          var y = l + e,
            I = c + i;
          if (M && f > y ? e = g ? f - y + e : d - w : !M && y + r > f + b && (e = g ? f + b - r - y + e : w - r), g || (S > h && I + h > v + S ? i += v + S - h - I : (h >= S || v > I) && (i += v - I)), g && (I + h > v + S + .49 || v > I) || !g && h > S + .49) {
            var x = this;
            t.dataSM("scroll-arrows") || t.dataSM("scroll-arrows", $([$('<span class="scroll-up"><span class="scroll-up-arrow"></span></span>')[0], $('<span class="scroll-down"><span class="scroll-down-arrow"></span></span>')[0]]).on({
              mouseenter: function mouseenter() {
                t.dataSM("scroll").up = $(this).hasClass("scroll-up"), x.menuScroll(t);
              },
              mouseleave: function mouseleave(e) {
                x.menuScrollStop(t), x.menuScrollOut(t, e);
              },
              "mousewheel DOMMouseScroll": function mousewheel_DOMMouseScroll(t) {
                t.preventDefault();
              }
            }).insertAfter(t));
            var A = ".smartmenus_scroll";
            if (t.dataSM("scroll", {
              y: this.cssTransforms3d ? 0 : i - m,
              step: 1,
              itemH: m,
              subH: h,
              arrowDownH: this.getHeight(t.dataSM("scroll-arrows").eq(1))
            }).on(getEventsNS({
              mouseover: function mouseover(e) {
                x.menuScrollOver(t, e);
              },
              mouseout: function mouseout(e) {
                x.menuScrollOut(t, e);
              },
              "mousewheel DOMMouseScroll": function mousewheel_DOMMouseScroll(e) {
                x.menuScrollMousewheel(t, e);
              }
            }, A)).dataSM("scroll-arrows").css({
              top: "auto",
              left: "0",
              marginLeft: e + (parseInt(t.css("border-left-width")) || 0),
              width: r - (parseInt(t.css("border-left-width")) || 0) - (parseInt(t.css("border-right-width")) || 0),
              zIndex: t.css("z-index")
            }).eq(g && this.opts.bottomToTopSubMenus ? 0 : 1).show(), this.isFixed()) {
              var C = {};
              C[touchEvents ? "touchstart touchmove touchend" : "pointerdown pointermove pointerup MSPointerDown MSPointerMove MSPointerUp"] = function (e) {
                x.menuScrollTouch(t, e);
              }, t.css({
                "touch-action": "none",
                "-ms-touch-action": "none"
              }).on(getEventsNS(C, A));
            }
          }
        }
        t.css({
          top: "auto",
          left: "0",
          marginLeft: e,
          marginTop: i - m
        });
      },
      menuScroll: function menuScroll(t, e, i) {
        var s,
          o = t.dataSM("scroll"),
          a = t.dataSM("scroll-arrows"),
          n = o.up ? o.upEnd : o.downEnd;
        if (!e && o.momentum) {
          if (o.momentum *= .92, s = o.momentum, .5 > s) return this.menuScrollStop(t), void 0;
        } else s = i || (e || !this.opts.scrollAccelerate ? this.opts.scrollStep : Math.floor(o.step));
        var r = t.dataSM("level");
        if (this.activatedItems[r - 1] && this.activatedItems[r - 1].dataSM("sub") && this.activatedItems[r - 1].dataSM("sub").is(":visible") && this.menuHideSubMenus(r - 1), o.y = o.up && o.y >= n || !o.up && n >= o.y ? o.y : Math.abs(n - o.y) > s ? o.y + (o.up ? s : -s) : n, t.css(this.cssTransforms3d ? {
          "-webkit-transform": "translate3d(0, " + o.y + "px, 0)",
          transform: "translate3d(0, " + o.y + "px, 0)"
        } : {
          marginTop: o.y
        }), mouse && (o.up && o.y > o.downEnd || !o.up && o.y < o.upEnd) && a.eq(o.up ? 1 : 0).show(), o.y == n) mouse && a.eq(o.up ? 0 : 1).hide(), this.menuScrollStop(t);else if (!e) {
          this.opts.scrollAccelerate && o.step < this.opts.scrollStep && (o.step += .2);
          var h = this;
          this.scrollTimeout = requestAnimationFrame(function () {
            h.menuScroll(t);
          });
        }
      },
      menuScrollMousewheel: function menuScrollMousewheel(t, e) {
        if (this.getClosestMenu(e.target) == t[0]) {
          e = e.originalEvent;
          var i = (e.wheelDelta || -e.detail) > 0;
          t.dataSM("scroll-arrows").eq(i ? 0 : 1).is(":visible") && (t.dataSM("scroll").up = i, this.menuScroll(t, !0));
        }
        e.preventDefault();
      },
      menuScrollOut: function menuScrollOut(t, e) {
        mouse && (/^scroll-(up|down)/.test((e.relatedTarget || "").className) || (t[0] == e.relatedTarget || $.contains(t[0], e.relatedTarget)) && this.getClosestMenu(e.relatedTarget) == t[0] || t.dataSM("scroll-arrows").css("visibility", "hidden"));
      },
      menuScrollOver: function menuScrollOver(t, e) {
        if (mouse && !/^scroll-(up|down)/.test(e.target.className) && this.getClosestMenu(e.target) == t[0]) {
          this.menuScrollRefreshData(t);
          var i = t.dataSM("scroll"),
            s = $(window).scrollTop() - t.dataSM("parent-a").offset().top - i.itemH;
          t.dataSM("scroll-arrows").eq(0).css("margin-top", s).end().eq(1).css("margin-top", s + this.getViewportHeight() - i.arrowDownH).end().css("visibility", "visible");
        }
      },
      menuScrollRefreshData: function menuScrollRefreshData(t) {
        var e = t.dataSM("scroll"),
          i = $(window).scrollTop() - t.dataSM("parent-a").offset().top - e.itemH;
        this.cssTransforms3d && (i = -(parseFloat(t.css("margin-top")) - i)), $.extend(e, {
          upEnd: i,
          downEnd: i + this.getViewportHeight() - e.subH
        });
      },
      menuScrollStop: function menuScrollStop(t) {
        return this.scrollTimeout ? (cancelAnimationFrame(this.scrollTimeout), this.scrollTimeout = 0, t.dataSM("scroll").step = 1, !0) : void 0;
      },
      menuScrollTouch: function menuScrollTouch(t, e) {
        if (e = e.originalEvent, isTouchEvent(e)) {
          var i = this.getTouchPoint(e);
          if (this.getClosestMenu(i.target) == t[0]) {
            var s = t.dataSM("scroll");
            if (/(start|down)$/i.test(e.type)) this.menuScrollStop(t) ? (e.preventDefault(), this.$touchScrollingSub = t) : this.$touchScrollingSub = null, this.menuScrollRefreshData(t), $.extend(s, {
              touchStartY: i.pageY,
              touchStartTime: e.timeStamp
            });else if (/move$/i.test(e.type)) {
              var o = void 0 !== s.touchY ? s.touchY : s.touchStartY;
              if (void 0 !== o && o != i.pageY) {
                this.$touchScrollingSub = t;
                var a = i.pageY > o;
                void 0 !== s.up && s.up != a && $.extend(s, {
                  touchStartY: i.pageY,
                  touchStartTime: e.timeStamp
                }), $.extend(s, {
                  up: a,
                  touchY: i.pageY
                }), this.menuScroll(t, !0, Math.abs(i.pageY - o));
              }
              e.preventDefault();
            } else void 0 !== s.touchY && ((s.momentum = 15 * Math.pow(Math.abs(i.pageY - s.touchStartY) / (e.timeStamp - s.touchStartTime), 2)) && (this.menuScrollStop(t), this.menuScroll(t), e.preventDefault()), delete s.touchY);
          }
        }
      },
      menuShow: function menuShow(t) {
        if ((t.dataSM("beforefirstshowfired") || (t.dataSM("beforefirstshowfired", !0), this.$root.triggerHandler("beforefirstshow.smapi", t[0]) !== !1)) && this.$root.triggerHandler("beforeshow.smapi", t[0]) !== !1 && (t.dataSM("shown-before", !0), canAnimate && t.stop(!0, !0), !t.is(":visible"))) {
          var e = t.dataSM("parent-a"),
            i = this.isCollapsible();
          if ((this.opts.keepHighlighted || i) && e.addClass("highlighted"), i) t.removeClass("sm-nowrap").css({
            zIndex: "",
            width: "auto",
            minWidth: "",
            maxWidth: "",
            top: "",
            left: "",
            marginLeft: "",
            marginTop: ""
          });else {
            if (t.css("z-index", this.zIndexInc = (this.zIndexInc || this.getStartZIndex()) + 1), (this.opts.subMenusMinWidth || this.opts.subMenusMaxWidth) && (t.css({
              width: "auto",
              minWidth: "",
              maxWidth: ""
            }).addClass("sm-nowrap"), this.opts.subMenusMinWidth && t.css("min-width", this.opts.subMenusMinWidth), this.opts.subMenusMaxWidth)) {
              var s = this.getWidth(t);
              t.css("max-width", this.opts.subMenusMaxWidth), s > this.getWidth(t) && t.removeClass("sm-nowrap").css("width", this.opts.subMenusMaxWidth);
            }
            this.menuPosition(t);
          }
          var o = function o() {
            t.css("overflow", "");
          };
          i ? canAnimate && this.opts.collapsibleShowFunction ? this.opts.collapsibleShowFunction.call(this, t, o) : t.show(this.opts.collapsibleShowDuration, o) : canAnimate && this.opts.showFunction ? this.opts.showFunction.call(this, t, o) : t.show(this.opts.showDuration, o), e.attr("aria-expanded", "true"), t.attr({
            "aria-expanded": "true",
            "aria-hidden": "false"
          }), this.visibleSubMenus.push(t), this.$root.triggerHandler("show.smapi", t[0]);
        }
      },
      popupHide: function popupHide(t) {
        this.hideTimeout && (clearTimeout(this.hideTimeout), this.hideTimeout = 0);
        var e = this;
        this.hideTimeout = setTimeout(function () {
          e.menuHideAll();
        }, t ? 1 : this.opts.hideTimeout);
      },
      popupShow: function popupShow(t, e) {
        if (!this.opts.isPopup) return alert('SmartMenus jQuery Error:\n\nIf you want to show this menu via the "popupShow" method, set the isPopup:true option.'), void 0;
        if (this.hideTimeout && (clearTimeout(this.hideTimeout), this.hideTimeout = 0), this.$root.dataSM("shown-before", !0), canAnimate && this.$root.stop(!0, !0), !this.$root.is(":visible")) {
          this.$root.css({
            left: t,
            top: e
          });
          var i = this,
            s = function s() {
              i.$root.css("overflow", "");
            };
          canAnimate && this.opts.showFunction ? this.opts.showFunction.call(this, this.$root, s) : this.$root.show(this.opts.showDuration, s), this.visibleSubMenus[0] = this.$root;
        }
      },
      refresh: function refresh() {
        this.destroy(!0), this.init(!0);
      },
      rootKeyDown: function rootKeyDown(t) {
        if (this.handleEvents()) switch (t.keyCode) {
          case 27:
            var e = this.activatedItems[0];
            if (e) {
              this.menuHideAll(), e[0].focus();
              var i = e.dataSM("sub");
              i && this.menuHide(i);
            }
            break;
          case 32:
            var s = $(t.target);
            if (s.is("a") && this.handleItemEvents(s)) {
              var i = s.dataSM("sub");
              i && !i.is(":visible") && (this.itemClick({
                currentTarget: t.target
              }), t.preventDefault());
            }
        }
      },
      rootOut: function rootOut(t) {
        if (this.handleEvents() && !this.isTouchMode() && t.target != this.$root[0] && (this.hideTimeout && (clearTimeout(this.hideTimeout), this.hideTimeout = 0), !this.opts.showOnClick || !this.opts.hideOnClick)) {
          var e = this;
          this.hideTimeout = setTimeout(function () {
            e.menuHideAll();
          }, this.opts.hideTimeout);
        }
      },
      rootOver: function rootOver(t) {
        this.handleEvents() && !this.isTouchMode() && t.target != this.$root[0] && this.hideTimeout && (clearTimeout(this.hideTimeout), this.hideTimeout = 0);
      },
      winResize: function winResize(t) {
        if (this.handleEvents()) {
          if (!("onorientationchange" in window) || "orientationchange" == t.type) {
            var e = this.isCollapsible();
            this.wasCollapsible && e || (this.activatedItems.length && this.activatedItems[this.activatedItems.length - 1][0].blur(), this.menuHideAll()), this.wasCollapsible = e;
          }
        } else if (this.$disableOverlay) {
          var i = this.$root.offset();
          this.$disableOverlay.css({
            top: i.top,
            left: i.left,
            width: this.$root.outerWidth(),
            height: this.$root.outerHeight()
          });
        }
      }
    }
  }), $.fn.dataSM = function (t, e) {
    return e ? this.data(t + "_smartmenus", e) : this.data(t + "_smartmenus");
  }, $.fn.removeDataSM = function (t) {
    return this.removeData(t + "_smartmenus");
  }, $.fn.smartmenus = function (options) {
    if ("string" == typeof options) {
      var args = arguments,
        method = options;
      return Array.prototype.shift.call(args), this.each(function () {
        var t = $(this).data("smartmenus");
        t && t[method] && t[method].apply(t, args);
      });
    }
    return this.each(function () {
      var dataOpts = $(this).data("sm-options") || null;
      if (dataOpts) try {
        dataOpts = eval("(" + dataOpts + ")");
      } catch (e) {
        dataOpts = null, alert('ERROR\n\nSmartMenus jQuery init:\nInvalid "data-sm-options" attribute value syntax.');
      }
      new $.SmartMenus(this, $.extend({}, $.fn.smartmenus.defaults, options, dataOpts));
    });
  }, $.fn.smartmenus.defaults = {
    isPopup: !1,
    mainMenuSubOffsetX: 0,
    mainMenuSubOffsetY: 0,
    subMenusSubOffsetX: 0,
    subMenusSubOffsetY: 0,
    subMenusMinWidth: "10em",
    subMenusMaxWidth: "20em",
    subIndicators: !0,
    subIndicatorsPos: "append",
    subIndicatorsText: "",
    scrollStep: 30,
    scrollAccelerate: !0,
    showTimeout: 250,
    hideTimeout: 500,
    showDuration: 0,
    showFunction: null,
    hideDuration: 0,
    hideFunction: function hideFunction(t, e) {
      t.fadeOut(200, e);
    },
    collapsibleShowDuration: 0,
    collapsibleShowFunction: function collapsibleShowFunction(t, e) {
      t.slideDown(200, e);
    },
    collapsibleHideDuration: 0,
    collapsibleHideFunction: function collapsibleHideFunction(t, e) {
      t.slideUp(200, e);
    },
    showOnClick: !1,
    hideOnClick: !0,
    noMouseOver: !1,
    keepInViewport: !0,
    keepHighlighted: !0,
    markCurrentItem: !1,
    markCurrentTree: !0,
    rightToLeftSubMenus: !1,
    bottomToTopSubMenus: !1,
    collapsibleBehavior: "default"
  }, $;
});
"use strict";

function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
/**
 * WhenInViewport 2.0.4
 * Author: dbrekalo
 * Plugin URL: https://github.com/dbrekalo/whenInViewport

 * MIT licensed:
 * http://www.opensource.org/licenses/mit-license.php

 * edit: added root ={} - fix error in line root.WhenInViewport = factory()
 */

(function (root, factory) {
  root = {};
  /* istanbul ignore next */
  if (typeof define === 'function' && define.amd) {
    define([], factory);
  } else if ((typeof module === "undefined" ? "undefined" : _typeof(module)) === 'object' && module.exports) {
    module.exports = factory();
  } else {
    root.WhenInViewport = factory();
  }
})(void 0, function () {
  var windowHeight;
  var scrollOffset;
  function WhenInViewport(element, callback, options) {
    events.setup();
    this.registryItem = registry.addItem(element, typeof callback === 'function' ? assign(options || {}, {
      callback: callback
    }) : callback);
    registry.processItem(this.registryItem);
  }
  WhenInViewport.prototype.stopListening = function () {
    registry.removeItem(this.registryItem);
    events.removeIfStoreEmpty();
  };
  WhenInViewport.defaults = {
    threshold: 0,
    context: null
  };
  assign(WhenInViewport, {
    setRateLimiter: function setRateLimiter(rateLimiter, rateLimitDelay) {
      events.rateLimiter = rateLimiter;
      if (rateLimitDelay) {
        events.rateLimitDelay = rateLimitDelay;
      }
      return this;
    },
    checkAll: function checkAll() {
      scrollOffset = getWindowScrollOffset();
      windowHeight = getWindowHeight();
      registry.adjustPositions(registry.processItem);
      events.removeIfStoreEmpty();
      return this;
    },
    destroy: function destroy() {
      registry.store = {};
      events.remove();
      delete events.scrollHandler;
      delete events.resizeHandler;
      return this;
    },
    registerAsJqueryPlugin: function registerAsJqueryPlugin($) {
      $.fn.whenInViewport = function (options, moreOptions) {
        var pluginOptions;
        var callbackProxy = function callbackProxy(callback) {
          return function (el) {
            callback.call(this, $(el));
          };
        };
        if (typeof options === 'function') {
          pluginOptions = $.extend({}, moreOptions, {
            callback: callbackProxy(options)
          });
        } else {
          pluginOptions = $.extend(options, {
            callback: callbackProxy(options.callback)
          });
        }
        return this.each(function () {
          if (pluginOptions.setupOnce) {
            !$.data(this, 'whenInViewport') && $.data(this, 'whenInViewport', new WhenInViewport(this, pluginOptions));
          } else {
            $.data(this, 'whenInViewport', new WhenInViewport(this, pluginOptions));
          }
        });
      };
      return this;
    }
  });
  function getWindowHeight() {
    /* istanbul ignore next */
    return 'innerHeight' in window ? window.innerHeight : document.documentElement.offsetHeight;
  }
  function getWindowScrollOffset() {
    /* istanbul ignore next */
    return 'pageYOffset' in window ? window.pageYOffset : document.documentElement.scrollTop || document.body.scrollTop;
  }
  function getElementOffset(element) {
    return element.getBoundingClientRect().top + getWindowScrollOffset();
  }
  function iterate(obj, callback, context) {
    for (var key in obj) {
      if (obj.hasOwnProperty(key)) {
        if (callback.call(context, obj[key], key) === false) {
          break;
        }
      }
    }
  }
  function assign(out) {
    for (var i = 1; i < arguments.length; i++) {
      iterate(arguments[i], function (obj, key) {
        out[key] = obj;
      });
    }
    return out;
  }
  var registry = {
    store: {},
    counter: 0,
    addItem: function addItem(element, options) {
      var storeKey = 'whenInViewport' + ++this.counter;
      var item = assign({}, WhenInViewport.defaults, options, {
        storeKey: storeKey,
        element: element,
        topOffset: getElementOffset(element)
      });
      return this.store[storeKey] = item;
    },
    adjustPositions: function adjustPositions(callback) {
      iterate(this.store, function (storeItem) {
        storeItem.topOffset = getElementOffset(storeItem.element);
        callback && callback.call(registry, storeItem);
      });
    },
    processAll: function processAll() {
      iterate(this.store, this.processItem, this);
    },
    processItem: function processItem(item) {
      if (scrollOffset + windowHeight >= item.topOffset - item.threshold) {
        this.removeItem(item);
        item.callback.call(item.context || window, item.element);
      }
    },
    removeItem: function removeItem(registryItem) {
      delete this.store[registryItem.storeKey];
    },
    isEmpty: function isEmpty() {
      var isEmpty = true;
      iterate(this.store, function () {
        return isEmpty = false;
      });
      return isEmpty;
    }
  };
  var events = {
    setuped: false,
    rateLimiter: function rateLimiter(callback, timeout) {
      return callback;
    },
    rateLimitDelay: 100,
    on: function on(eventName, callback) {
      /* istanbul ignore next */
      if (window.addEventListener) {
        window.addEventListener(eventName, callback, false);
      } else if (window.attachEvent) {
        window.attachEvent(eventName, callback);
      }
      return this;
    },
    off: function off(eventName, callback) {
      /* istanbul ignore next */
      if (window.removeEventListener) {
        window.removeEventListener(eventName, callback, false);
      } else if (window.detachEvent) {
        window.detachEvent('on' + eventName, callback);
      }
      return this;
    },
    setup: function setup() {
      var self = this;
      if (!this.setuped) {
        scrollOffset = getWindowScrollOffset();
        windowHeight = getWindowHeight();
        this.scrollHandler = this.scrollHandler || this.rateLimiter(function () {
          scrollOffset = getWindowScrollOffset();
          registry.processAll();
          self.removeIfStoreEmpty();
        }, this.rateLimitDelay);
        this.resizeHandler = this.resizeHandler || this.rateLimiter(function () {
          windowHeight = getWindowHeight();
          registry.adjustPositions(registry.processItem);
          self.removeIfStoreEmpty();
        }, this.rateLimitDelay);
        this.on('scroll', this.scrollHandler).on('resize', this.resizeHandler);
        this.setuped = true;
      }
    },
    removeIfStoreEmpty: function removeIfStoreEmpty() {
      registry.isEmpty() && this.remove();
    },
    remove: function remove() {
      if (this.setuped) {
        this.off('scroll', this.scrollHandler).off('resize', this.resizeHandler);
        this.setuped = false;
      }
    }
  };
  if (typeof window !== 'undefined') {
    var $ = window.jQuery || window.$;
    $ && WhenInViewport.registerAsJqueryPlugin($);
  }
  return WhenInViewport;
});