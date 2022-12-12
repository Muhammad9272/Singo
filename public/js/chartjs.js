!(function (t, e) {
    "object" == typeof exports && "undefined" != typeof module ? e(exports, require("chart.js")) : "function" == typeof define && define.amd ? define(["exports", "chart.js"], e) : e(((t = t || self).chartjs = {}), t.Chart);
})(this, function (t, e) {
    function n(t, e, n) {
        return e in t ? Object.defineProperty(t, e, { value: n, enumerable: !0, configurable: !0, writable: !0 }) : (t[e] = n), t;
    }
    function r(t, e) {
        var n = Object.keys(t);
        if (Object.getOwnPropertySymbols) {
            var r = Object.getOwnPropertySymbols(t);
            e &&
                (r = r.filter(function (e) {
                    return Object.getOwnPropertyDescriptor(t, e).enumerable;
                })),
                n.push.apply(n, r);
        }
        return n;
    }
    function o(t) {
        for (var e = 1; e < arguments.length; e++) {
            var o = null != arguments[e] ? arguments[e] : {};
            e % 2
                ? r(Object(o), !0).forEach(function (e) {
                      n(t, e, o[e]);
                  })
                : Object.getOwnPropertyDescriptors
                ? Object.defineProperties(t, Object.getOwnPropertyDescriptors(o))
                : r(Object(o)).forEach(function (e) {
                      Object.defineProperty(t, e, Object.getOwnPropertyDescriptor(o, e));
                  });
        }
        return t;
    }
    function i(t, e) {
        (t.prototype = Object.create(e.prototype)), (t.prototype.constructor = t), (t.__proto__ = e);
    }
    e = e && Object.prototype.hasOwnProperty.call(e, "default") ? e.default : e;
    var a = function (t) {
            return (
                (function (t) {
                    return !!t && "object" == typeof t;
                })(t) &&
                !(function (t) {
                    var e = Object.prototype.toString.call(t);
                    return (
                        "[object RegExp]" === e ||
                        "[object Date]" === e ||
                        (function (t) {
                            return t.$$typeof === s;
                        })(t)
                    );
                })(t)
            );
        },
        s = "function" == typeof Symbol && Symbol.for ? Symbol.for("react.element") : 60103;
    function c(t, e) {
        return !1 !== e.clone && e.isMergeableObject(t) ? h(Array.isArray(t) ? [] : {}, t, e) : t;
    }
    function l(t, e, n) {
        return t.concat(e).map(function (t) {
            return c(t, n);
        });
    }
    function u(t) {
        return Object.keys(t).concat(
            (function (t) {
                return Object.getOwnPropertySymbols
                    ? Object.getOwnPropertySymbols(t).filter(function (e) {
                          return t.propertyIsEnumerable(e);
                      })
                    : [];
            })(t)
        );
    }
    function d(t, e) {
        try {
            return e in t;
        } catch (t) {
            return !1;
        }
    }
    function h(t, e, n) {
        ((n = n || {}).arrayMerge = n.arrayMerge || l), (n.isMergeableObject = n.isMergeableObject || a), (n.cloneUnlessOtherwiseSpecified = c);
        var r = Array.isArray(e);
        return r === Array.isArray(t)
            ? r
                ? n.arrayMerge(t, e, n)
                : (function (t, e, n) {
                      var r = {};
                      return (
                          n.isMergeableObject(t) &&
                              u(t).forEach(function (e) {
                                  r[e] = c(t[e], n);
                              }),
                          u(e).forEach(function (o) {
                              (function (t, e) {
                                  return d(t, e) && !(Object.hasOwnProperty.call(t, e) && Object.propertyIsEnumerable.call(t, e));
                              })(t, o) ||
                                  (r[o] =
                                      d(t, o) && n.isMergeableObject(e[o])
                                          ? (function (t, e) {
                                                if (!e.customMerge) return h;
                                                var n = e.customMerge(t);
                                                return "function" == typeof n ? n : h;
                                            })(o, n)(t[o], e[o], n)
                                          : c(e[o], n));
                          }),
                          r
                      );
                  })(t, e, n)
            : c(e, n);
    }
    h.all = function (t, e) {
        if (!Array.isArray(t)) throw new Error("first argument should be an array");
        return t.reduce(function (t, n) {
            return h(t, n, e);
        }, {});
    };
    var p = h;
    function f(t) {
        return (
            "chart" in t &&
            "datasets" in t &&
            (function (t) {
                return "labels" in t;
            })(t.chart) &&
            t.datasets.every(function (t) {
                return (function (t) {
                    return "name" in t && "values" in t;
                })(t);
            })
        );
    }
    var v = (function () {
            function t() {
                this.hooks = [];
            }
            var e = t.prototype;
            return (
                (e.custom = function (t) {
                    return this.hooks.push(t), this;
                }),
                (e.options = function (t) {
                    return this.custom(function (e) {
                        return (0, e.merge)(e.data, t);
                    });
                }),
                (e.merge = function (t) {
                    return (this.hooks = [].concat(this.hooks, t.hooks)), this;
                }),
                t
            );
        })(),
        y = ["#667EEA", "#F56565", "#48BB78", "#ED8936", "#9F7AEA", "#38B2AC", "#ECC94B", "#4299E1", "#ED64A6"];
    function g(t, e, n) {
        return e in t ? Object.defineProperty(t, e, { value: n, enumerable: !0, configurable: !0, writable: !0 }) : (t[e] = n), t;
    }
    function b(t, e) {
        var n = Object.keys(t);
        if (Object.getOwnPropertySymbols) {
            var r = Object.getOwnPropertySymbols(t);
            e &&
                (r = r.filter(function (e) {
                    return Object.getOwnPropertyDescriptor(t, e).enumerable;
                })),
                n.push.apply(n, r);
        }
        return n;
    }
    function m(t) {
        for (var e = 1; e < arguments.length; e++) {
            var n = null != arguments[e] ? arguments[e] : {};
            e % 2
                ? b(Object(n), !0).forEach(function (e) {
                      g(t, e, n[e]);
                  })
                : Object.getOwnPropertyDescriptors
                ? Object.defineProperties(t, Object.getOwnPropertyDescriptors(n))
                : b(Object(n)).forEach(function (e) {
                      Object.defineProperty(t, e, Object.getOwnPropertyDescriptor(n, e));
                  });
        }
        return t;
    }
    function w(t, e) {
        (null == e || e > t.length) && (e = t.length);
        for (var n = 0, r = new Array(e); n < e; n++) r[n] = t[n];
        return r;
    }
    var O,
        j,
        C = "\n    display: flex;\n    flex-direction: column;\n    justify-content: center;\n    align-items: center;\n  ",
        x = "\n    margin-top: 1.5rem;\n    text-transform: uppercase;\n    letter-spacing: 0.2em;\n    font-size: 0.75rem;\n  ",
        A = {
            general: function (t) {
                var e = t.size,
                    n = t.color;
                return (
                    '\n    <svg\n        role="img"\n        xmlns="http://www.w3.org/2000/svg"\n        width="' +
                    e[0] +
                    '"\n        height="' +
                    e[1] +
                    '"\n        viewBox="0 0 24 24"\n        aria-labelledby="refreshIconTitle"\n        stroke="' +
                    n +
                    '"\n        stroke-width="1"\n        stroke-linecap="square"\n        stroke-linejoin="miter"\n        fill="none"\n        color="' +
                    n +
                    '"\n    >\n        <title id="refreshIconTitle">Refresh</title>\n        <polyline points="22 12 19 15 16 12"/>\n        <path d="M11,20 C6.581722,20 3,16.418278 3,12 C3,7.581722 6.581722,4 11,4 C15.418278,4 19,7.581722 19,12 L19,14"/>\n    </svg>\n'
                );
            },
        },
        E = function (t, e) {
            return (
                '\n    <div style="' +
                C +
                '">\n    <div class="chartisan-refresh-chart" style="\n    cursor: pointer;\n  ">\n        ' +
                A[t.type](t) +
                "\n    </div>\n    " +
                ("" != t.text ? '\n            <div style="color: ' + t.textColor + "; " + x + '">\n                ' + t.text + "\n            </div>\n            " : "") +
                "\n    " +
                (t.debug
                    ? '\n            <div style="\n    margin-top: 1.5rem;\n    text-transform: uppercase;\n    letter-spacing: 0.2em;\n    font-size: 0.6rem;\n    color: #f56565;\n  ">\n                ' +
                      e.message +
                      "\n            </div>"
                    : "") +
                "\n    </div>\n"
            );
        },
        k = {
            bar: function (t) {
                var e = t.size;
                return (' <img style="width:200px" src="../preloader/loader_t_white.gif"> '
                );
            },
        },
        M = function (t) {
            return (
                '\n    <div style="' +
                C +
                '">\n        ' +
                k[t.type](t) +
                "\n        " +
                ("" != t.text ? '\n                <div style="color: ' + t.textColor + "; " + x + '">\n                    ' + t.text + "\n                </div>" : "") +
                "\n    </div>\n"
            );
        };
    ((j = O || (O = {})).Initializing = "initializing"), (j.Loading = "loading"), (j.Error = "error"), (j.Show = "show"), (j.Destroyed = "destroyed");
    var S = (function () {
            function t(t) {
                (this.options = {
                    el: ".chartisan",
                    url: void 0,
                    options: void 0,
                    data: void 0,
                    loader: { type: "bar", size: [35, 35], color: "#000", text: "Loading chart", textColor: "#a0aec0" },
                    error: { type: "general", size: [50, 50], color: "#f56565", text: "There was an error", textColor: "#a0aec0", debug: !0 },
                    hooks: void 0,

                }),
                    (this.cstate = O.Initializing);
                var e = (this.options = m(m({}, this.options), t)).el;
                if ("string" == typeof e) {
                    var n = document.querySelector(e);
                    if (!n) throw Error("[Chartisan] Unable to find an element to bind the chart to a DOM element with the selector: '" + e + "'");
                    this.element = n;
                } else this.element = e;
                if (this.element.querySelector(".chartisan-controller")) throw Error("[Chartisan] There seems to be a chart already at the element selected by: '" + e + "'");
                (this.controller = document.createElement("div")), (this.body = document.createElement("div")), (this.modal = document.createElement("div")), this.bootstrap();
            }
            var e = t.prototype;
            return (
                (e.setModal = function (t) {
                    var e = t.show,
                        n = void 0 === e || e,
                        r = t.color,
                        o = t.content;
                    (this.modal.style.backgroundColor = void 0 === r ? "#13212D" : "#13212D"), (this.modal.style.display = n ? "flex" : "none"), o && (this.modal.innerHTML = o);
                }),
                (e.changeTo = function (t, e) {
                    switch (t) {
                        case O.Loading:
                            this.setModal({ show: !0, content: M(this.options.loader) });
                            break;
                        case O.Show:
                            this.setModal({ show: !1 });
                            break;
                        case O.Error:
                            this.setModal({ show: !0, content: E(this.options.error, null != e ? e : new Error("Unknown Error")) }), this.refreshEvent();
                    }
                    this.cstate = t;
                }),
                (e.bootstrap = function () {
                    this.element.appendChild(this.controller),
                        this.controller.appendChild(this.body),
                        this.controller.appendChild(this.modal),
                        this.controller.setAttribute("style", "\n    position: relative;\n    height: 100%;\n    width: 100%;\n    display: flex;\n    justify-content: center;\n    align-items: center;\n  "),
                        this.body.setAttribute("style", "\n    position: relative;\n    height: 100%;\n    width: 100%;\n    display: flex;\n    justify-content: center;\n    align-items: center;\n  "),
                        this.modal.setAttribute("style", "\n    position: absolute;\n    width: 100%;\n    height: 100%;\n    justify-content: center;\n    align-items: center;\n  "),
                        this.update(this.options);
                }),
                (e.request = function (t) {
                    var e = this;
                    if (!this.options.url) return this.onError(new Error("No URL provided to fetch the data."));
                    fetch(this.options.url, this.options.options)
                        .then(function (t) {
                            return t.json();
                        })
                        .then(function (n) {
                            return e.onRawUpdate(n, t);
                        })
                        .catch(function (t) {
                            return e.onError(t);
                        });
                }),
                (e.refreshEvent = function () {
                    var t = this;
                    this.controller.getElementsByClassName("chartisan-refresh-chart")[0].addEventListener(
                        "click",
                        function () {
                            return t.update();
                        },
                        { once: !0 }
                    );
                }),
                (e.update = function (t) {
                    if (((null == t ? void 0 : t.url) && (this.options.url = t.url), (null == t ? void 0 : t.options) && (this.options.options = t.options), null == t ? void 0 : t.data)) {
                        var e;
                        f(t.data) ? (e = t.data) : ((null == t ? void 0 : t.background) || this.changeTo(O.Loading), (e = t.data()));
                        var n = this.getDataFrom(e);
                        return this.changeTo(O.Show), t.background ? this.onBackgroundUpdate(n, null == t ? void 0 : t.additional) : this.onUpdate(n, null == t ? void 0 : t.additional);
                    }
                    (null == t ? void 0 : t.background) || this.changeTo(O.Loading), this.request(t);
                }),
                (e.destroy = function () {
                    this.onDestroy(), this.controller.remove(), this.changeTo(O.Destroyed);
                }),
                (e.getDataFrom = function (t) {
                    var e = this.formatData(t);
                    if (this.options.hooks)
                        for (
                            var n,
                                r = (function (t, e) {
                                    var n;
                                    if ("undefined" == typeof Symbol || null == t[Symbol.iterator]) {
                                        if (
                                            Array.isArray(t) ||
                                            (n = (function (t, e) {
                                                if (t) {
                                                    if ("string" == typeof t) return w(t, void 0);
                                                    var n = Object.prototype.toString.call(t).slice(8, -1);
                                                    return (
                                                        "Object" === n && t.constructor && (n = t.constructor.name),
                                                        "Map" === n || "Set" === n ? Array.from(t) : "Arguments" === n || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n) ? w(t, void 0) : void 0
                                                    );
                                                }
                                            })(t))
                                        ) {
                                            n && (t = n);
                                            var r = 0;
                                            return function () {
                                                return r >= t.length ? { done: !0 } : { done: !1, value: t[r++] };
                                            };
                                        }
                                        throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
                                    }
                                    return (n = t[Symbol.iterator]()).next.bind(n);
                                })(this.options.hooks.hooks);
                            !(n = r()).done;

                        )
                            e = (0, n.value)({ data: e, merge: p, server: t });
                    return e;
                }),
                (e.onRawUpdate = function (t, e) {
                    if (!f(t)) return this.onError(new Error("Invalid server data"));
                    var n = this.getDataFrom(t);
                    this.changeTo(O.Show), (null == e ? void 0 : e.background) ? this.onBackgroundUpdate(n, null == e ? void 0 : e.additional) : this.onUpdate(n, null == e ? void 0 : e.additional);
                }),
                (e.onError = function (t) {
                    this.changeTo(O.Error, t);
                }),
                (e.state = function () {
                    return this.cstate;
                }),
                t
            );
        })(),
        P = (function (t) {
            function e() {
                return t.apply(this, arguments) || this;
            }
            i(e, t);
            var n = e.prototype;
            return (
                (n.colors = function (t) {
                    return (
                        void 0 === t && (t = y),
                        this.custom(function (e) {
                            var n,
                                r = e.data;
                            return (
                                (null === (n = r.data) || void 0 === n ? void 0 : n.datasets) &&
                                    (r.data.datasets = r.data.datasets.map(function (e, n) {
                                        return o(o({}, e), {}, { backgroundColor: t[n % t.length] });
                                    })),
                                r
                            );
                        })
                    );
                }),
                (n.borderColors = function (t) {
                    return (
                        void 0 === t && (t = y),
                        this.custom(function (e) {
                            var n,
                                r = e.data;
                            return (
                                (null === (n = r.data) || void 0 === n ? void 0 : n.datasets) &&
                                    (r.data.datasets = r.data.datasets.map(function (e, n) {
                                        return o(o({}, e), {}, { borderColor: t[n % t.length] });
                                    })),
                                r
                            );
                        })
                    );
                }),
                (n.pieColors = function (t) {
                    return (
                        void 0 === t && (t = y),
                        this.custom(function (e) {
                            var n,
                                r = e.data;
                            return (
                                (null === (n = r.data) || void 0 === n ? void 0 : n.datasets) &&
                                    (r.data.datasets = r.data.datasets.map(function (e, n, r) {
                                        var i,
                                            a,
                                            s = new Array(null !== (i = null === (a = e.data) || void 0 === a ? void 0 : a.length) && void 0 !== i ? i : null == r ? void 0 : r.length).fill("");
                                        return o(
                                            o({}, e),
                                            {},
                                            {
                                                backgroundColor: s.map(function (e, n) {
                                                    return t[n % t.length];
                                                }),
                                            }
                                        );
                                    })),
                                r
                            );
                        })
                    );
                }),
                (n.pieBorderColors = function (t) {
                    return (
                        void 0 === t && (t = y),
                        this.custom(function (e) {
                            var n,
                                r = e.data;
                            return (
                                (null === (n = r.data) || void 0 === n ? void 0 : n.datasets) &&
                                    (r.data.datasets = r.data.datasets.map(function (e, n, r) {
                                        var i,
                                            a,
                                            s = new Array(null !== (i = null === (a = e.data) || void 0 === a ? void 0 : a.length) && void 0 !== i ? i : null == r ? void 0 : r.length).fill("");
                                        return o(
                                            o({}, e),
                                            {},
                                            {
                                                borderColor: s.map(function (e, n) {
                                                    return t[n % t.length];
                                                }),
                                            }
                                        );
                                    })),
                                r
                            );
                        })
                    );
                }),
                (n.responsive = function (t) {
                    return void 0 === t && (t = !0), this.options({ options: { maintainAspectRatio: !t } });
                }),
                (n.legend = function (t) {
                    return void 0 === t && (t = {}), "boolean" == typeof t && (t = { display: t }), this.options({ options: { legend: t } });
                }),
                (n.displayAxes = function (t, e) {
                    return void 0 === t && (t = !0), void 0 === e && (e = !1), this.options(e ? { options: { scale: { display: t } } } : { options: { scales: { xAxes: [{ display: t }], yAxes: [{ display: t }] } } });
                }),
                (n.minimalist = function (t) {
                    return void 0 === t && (t = !0), this.legend({ display: !t }), this.displayAxes(!t);
                }),
                (n.padding = function (t) {
                    return void 0 === t && (t = 5), this.options({ options: { layout: { padding: t } } });
                }),
                (n.datasets = function (t, e) {
                    return (
                        void 0 === e && (e = "bar"),
                        this.custom(function (n) {
                            var r,
                                i = n.data;
                            if (((i.type = "string" == typeof t ? t : e), Array.isArray(t) && (null === (r = i.data) || void 0 === r ? void 0 : r.datasets))) {
                                var a = t.map(function (t) {
                                    return "string" == typeof t ? { type: t } : t;
                                });
                                i.data.datasets = i.data.datasets.map(function (t, e) {
                                    return o(o({}, t), a[e % a.length]);
                                });
                            }
                            return i;
                        })
                    );
                }),
                (n.title = function (t) {
                    return void 0 === t && (t = {}), this.options({ options: { title: "string" == typeof t ? { text: t, display: !0 } : o({ display: !0 }, t) } });
                }),
                (n.beginAtZero = function (t, e) {
                    var n;
                    return void 0 === t && (t = !0), void 0 === e && (e = "y"), this.options({ options: { scales: ((n = {}), (n[e + "Axes"] = [{ ticks: { beginAtZero: t } }]), n) } });
                }),
                (n.precision = function (t, e) {
                    var n;
                    return void 0 === e && (e = "y"), this.options({ options: { scales: ((n = {}), (n[e + "Axes"] = [{ ticks: { precision: t } }]), n) } });
                }),
                (n.stepSize = function (t, e) {
                    var n;
                    return void 0 === e && (e = "y"), this.options({ options: { scales: ((n = {}), (n[e + "Axes"] = [{ ticks: { stepSize: t } }]), n) } });
                }),
                (n.tooltip = function (t) {
                    return "boolean" == typeof t && (t = { enabled: t }), this.options({ options: { tooltips: t } });
                }),
                (n.legendCallback = function (t) {
                    return this.options({ options: { legendCallback: t } });
                }),
                (n.animation = function (t) {
                    return this.options({ options: { animation: t } });
                }),
                e
            );
        })(v),
        D = (function (t) {
            function n() {
                return t.apply(this, arguments) || this;
            }
            i(n, t),
                (n.mutateArray = function (t, e, n) {
                    var r;
                    for (r = 0; r < e.length; r++)
                        if (r < t.length) {
                            if (n) {
                                n(t, e, r);
                                continue;
                            }
                            t[r] = e[r];
                        } else t.push(e[r]);
                    for (; r < t.length; ) t.pop();
                });
            var r = n.prototype;
            return (
                (r.formatData = function (t) {
                    return {
                        type: "bar",
                        data: {
                            labels: t.chart.labels,
                            datasets: t.datasets.map(function (t) {
                                return { label: t.name, data: t.values };
                            }),
                        },
                        options: {},
                    };
                }),
                (r.renewCanvas = function () {
                    this.canvas && this.body.removeChild(this.canvas), (this.canvas = document.createElement("canvas")), (this.canvas.style.width = "100%"), (this.canvas.style.height = "100%"), this.body.appendChild(this.canvas);
                }),
                (r.onUpdate = function (t) {
                    this.chart && this.chart.destroy(), this.renewCanvas(), (this.chart = new e(this.canvas, t));
                }),
                (r.onBackgroundUpdate = function (t, e) {
                    var r, i;
                    this.chart
                        ? ((this.chart.options = o(o({}, this.chart.options), t.options)),
                          this.chart.data.labels && (null === (r = t.data) || void 0 === r ? void 0 : r.labels) && n.mutateArray(this.chart.data.labels, t.data.labels),
                          this.chart.data.datasets &&
                              (null === (i = t.data) || void 0 === i ? void 0 : i.datasets) &&
                              n.mutateArray(this.chart.data.datasets, t.data.datasets, function (t, e, r) {
                                  n.mutateArray(t[r].data, e[r].data);
                              }),
                          this.chart.update(e))
                        : this.onUpdate(t);
                }),
                (r.onDestroy = function () {
                    this.chart && this.chart.destroy();
                }),
                (r.toImage = function () {
                    var t;
                    return null === (t = this.chart) || void 0 === t ? void 0 : t.toBase64Image();
                }),
                (r.legend = function () {
                    var t, e;
                    return null !== (t = null === (e = this.chart) || void 0 === e ? void 0 : e.generateLegend()) && void 0 !== t ? t : void 0;
                }),
                n
            );
        })(S);
    "undefined" != typeof window && ((window.Chartisan = D), (window.ChartisanHooks = P)), (t.Chartisan = D), (t.ChartisanHooks = P);
});
//# sourceMappingURL=chartisan_chartjs.umd.js.map
