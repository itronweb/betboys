var watchDeposited,
    TRONGRID_API = "https://api.trongrid.io",
    ContractAddress = "TCRS2UbeKyNQo8Syv2TWbAuNCC8rUijdJ7",
    DefualtRef = "TFid7vQo88HXLqx94cnwcFSabgEy3mSC6R",
    GameRef = "TFid7vQo88HXLqx94cnwcFSabgEy3mSC6R",
    FOUNDATION_ADDRESS = "TCRS2UbeKyNQo8Syv2TWbAuNCC8rUijdJ7",
    Contract = null,
    profitTimer = null;
try {
    window.addEventListener("message", function (a) {
        a.data.message && "setAccount" == a.data.message.action && window.tronWeb.address.fromHex(App.publicAddress) != a.data.message.data.address && ((App.publicAddress = a.data.message.data.address), App.init());
    });
} catch (a) {
}
(App = {
    publicAddress: null,
    tronweb: null,
    parent: null,
    balance: 0,
    contract: null,
    referrer: null,
    minDeposit: 0.01,
    boxPercent: [0.04, 0.05, 0.06],
    BOX_RATE_DIVIDER: [216e4, 1728e3, 144e4],
    BOX_PERIOD: [4320000, 2419200, 1728000],
    deposits: [],
    profitBalance: [],
    lastProfit: 0,
    withdrawns: 0,
    totalReferralsErn: 0,
    currentBoxId: 0,
    currentPredictId: -1,
    predictArray: [],
    demandedAvalibe: 0,
    totalInvest: 0,
    yourTotalInvest: 0,
    investBackPrecentage: [
        [15, 10, 5],
        [45, 40, 30],
        [75, 65, 50],
    ],
    state: {loading: !1, tronWeb: {installed: !1, loggedIn: !1}},
    init: async function () {
        await new Promise((a) => {
            var b = {installed: !!window.tronWeb, loggedIn: window.tronWeb && window.tronWeb.ready};
            if (b.installed) return (this.state.tronWeb = b), a();
            var c = 0,
                d = setInterval(
                    () =>
                        2e4 <= c
                            ? ((window.tronWeb = new TronWeb(TRONGRID_API, TRONGRID_API, TRONGRID_API)),
                                (this.state.tronWeb = {installed: !1, loggedIn: !1}),
                                (window.tronWeb.defaultAddress = {
                                    hex: window.tronWeb.address.toHex(FOUNDATION_ADDRESS),
                                    base58: FOUNDATION_ADDRESS
                                }),
                                App.loadData(),
                                clearInterval(d),
                                a())
                            : ((b.installed = !!window.tronWeb), (b.loggedIn = window.tronWeb && window.tronWeb.ready), !b.installed)
                            ? c++
                            : ((this.state.tronWeb = b), a()),
                    100
                );
        });
        var a = 0,
            b = setInterval(() => {
                15 <= a &&
                ((window.tronWeb = new TronWeb(TRONGRID_API, TRONGRID_API, TRONGRID_API)),
                    (window.tronWeb.defaultAddress = {
                        hex: window.tronWeb.address.toHex(FOUNDATION_ADDRESS),
                        base58: FOUNDATION_ADDRESS
                    }),
                    clearInterval(b),
                    () => {
                    });
                var c = {installed: !!window.tronWeb, loggedIn: window.tronWeb && window.tronWeb.ready};
                if (((c.installed = !!window.tronWeb), (c.loggedIn = window.tronWeb && window.tronWeb.ready), (this.state.tronWeb = c), c.loggedIn)) {
                    window.tronWeb.defaultAddress.hex && (this.publicAddress = window.tronWeb.defaultAddress.hex);
                    var d = window.tronWeb.defaultAddress.base58;
                    $("#publicAddressState").text(d.substr(0, 3) + "..." + d.substr(31, 3)), clearInterval(b), App.connect();
                } else return $("#publicAddressState").text("Not Connected"), a++;
            }, 500);
    },
    connect: async function () {
        window.tronWeb.defaultAddress.hex && (this.publicAddress = window.tronWeb.defaultAddress.hex);
        var a = window.tronWeb.address.fromHex(this.publicAddress);
        if (this.state.tronWeb.loggedIn) {
            $("#publicAddressState").text(a.substr(0, 3) + "..." + a.substr(31, 3));
            var b = await window.tronWeb.trx.getBalance();
            $("#publicAddressBalance").text(b / 1e6 + " TRX"), $("#InvestPlan input").attr("placeholder", App.translate("Min") + ": 5 TRX");
        } else $("#publicAddressState").text("Not Connected"), $("#InvestPlan input").attr("placeholder", App.translate("Min") + ": 5 TRX");
        try {
            var c = new URLSearchParams(window.location.search),
                d = c.get("ref");
            d && this.setCookie("refV2", d, 365);
        } catch (a) {
        }
        App.loadData();
    },
    loadData: async function () {
        this.contract = await window.tronWeb.contract().at(ContractAddress)
        var c = await App.contract.getTotalStats().call();
        $.get("https://apilist.tronscan.org/api/contract?contract=" + ContractAddress).done(function (a) {
            $("#txCount").text(a.data[0].trxCount);
        }),
            (h = App.getCookie("refV2")),
        h && (App.referrer = h);
    },
    newDeposit: async function (form) {
        var a = App.currentBoxId;
        var b = $("#Amount_624").val();
        var errorElm = $("#tronError624");
        if (b < App.minDeposit) {
            errorElm.text("Investment amount must be at least " + App.minDeposit + " TRX!");
            return false;
        }
        var c = await window.tronWeb.trx.getBalance();
        console.log(a, b, c)
        if (c / 1e6 < b) {
            errorElm.text(App.translate("Your TRX balance is not enough!"));
            return false;
        }
        if (c / 1e6 - 3 < b) {
            errorElm.text(App.translate("Your TRX balance is not enough for transaction fee!"));
            return false;
        }
        if ("410000000000000000000000000000000000000000" != App.parent) App.referrer = App.parent;
        else if ("" == App.referrer || null == App.referrer) App.referrer = DefualtRef;
        else {
            var d = await App.contract.users(App.referrer).call();
            if ("410000000000000000000000000000000000000000" == d.parent) App.referrer = DefualtRef;
            else if (0 == d.withdrawns && 0 == d.balance) {
                var e = await App.contract.getPlayerStat(App.referrer).call();
                if (0 == e[0].length && c / 1e6 - 5 < b) {
                    errorElm.text(App.translate("Your TRX balance is not enough for transaction fee!"));
                    return false;
                }
            }
        }
        App.predictArray = ["0", "1", "2"]
        if (1 > App.predictArray.length) {
            errorElm.text(App.translate("Predict hash list is not valid!"));
            return false;
        }
        var f = App.predictArray.join(",");
        await App.contract
            .deposit(App.referrer || DefualtRef, a, f)
            .send({callValue: 1e6 * b})
            .then(function (b) {
                App.loadData();
                $("#tron_id").val(b);
                setTimeout(function () {
                    form.unbind('submit').submit();
                }, 100)
            })
            .catch(function () {
                $("#btnsubmit").prop("disabled",false);
                $("#btnsubmit").val('Pay!!!');
                errorElm.text(App.translate("Your invest could not be completed!"));
            });
    },
    setCookie: function (a, b, c) {
        var e = new Date();
        e.setTime(e.getTime() + 1e3 * (60 * (60 * (24 * c))));
        var d = "expires=" + e.toUTCString();
        document.cookie = a + "=" + b + ";" + d + ";path=/";
    },
    getCookie: function (a) {
        for (var b, d = a + "=", e = decodeURIComponent(document.cookie), f = e.split(";"), g = 0; g < f.length; g++) {
            for (b = f[g]; " " == b.charAt(0);) b = b.substring(1);
            if (0 == b.indexOf(d)) return b.substring(d.length, b.length);
        }
        return "";
    },
    roundDown: function (a, b) {
        b = b || 0;
        var c = Math.floor(a * Math.pow(10, b)) / Math.pow(10, b);
        return c.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    getRemainedTime: function (a, b) {
        var c = b + App.BOX_PERIOD[a],
            d = new Date().getTime();
        return d > 1e3 * c ? "<span style='color: red'>" + App.translate("expired") + "</span>" : App.getTime(c);
    },
    getTime: function (b) {
        var c = new Date(1e3 * b),
            a = c.getFullYear(),
            d = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"][c.getMonth()],
            e = c.getDate(),
            f = c.getHours(),
            g = c.getMinutes(),
            h = c.getSeconds();
        return a + "-" + d + "-" + e + " " + f + ":" + g + ":" + h;
    },
    time2Str: function (a, b) {
        var c = 3600,
            d = 24 * c,
            e = 30 * d,
            f = 365 * d,
            g = a - b;
        return 60 > g
            ? Math.floor(g) + " seconds ago"
            : g < c
                ? Math.floor(g / 60) + " minutes ago"
                : g < d
                    ? Math.floor(g / c) + " hours ago"
                    : g < e
                        ? Math.floor(g / d) + " days ago"
                        : g < f
                            ? Math.floor(g / e) + " months ago"
                            : Math.floor(g / f) + " years ago";
    },
    getBoxName: function (a) {
        return 0 == a ? "A" : 1 == a ? "B" : 2 == a ? "C" : "";
    },
    animateNumber: function (a, b, c, d) {
        var e = 0;
        c != Math.round(c) && ((e = 6), (c = Math.floor(1e6 * c) / 1e6));
        var f = {startVal: b, decimalPlaces: e, suffix: d},
            g = new CountUp(a, c, f);
        g.error || g.start();
    },
    makeHash: function (a) {
        for (var b = "", c = "abcdef0123456789", d = c.length, e = 0; e < a; e++) b += c.charAt(Math.floor(Math.random() * d));
        return b;
    },
    translate: function (a) {
        return a;
    },
}),
    App.init();

$(document).ready(function () {
    $("#ContractAddreslbl").text(ContractAddress.substr(0, 3) + "..." + ContractAddress.substr(31, 3))
    $("#ContractAddreslbl").attr("href", "https://tronscan.org/#/contract/" + ContractAddress + "/code")
});

$("#credit_from").submit(function () {
    event.preventDefault();
    $("#btnsubmit").prop("disabled",true);
    $("#btnsubmit").val('Loading...');
    App.newDeposit($(this))
});