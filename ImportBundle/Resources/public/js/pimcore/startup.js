pimcore.registerNS("pimcore.plugin.ImportBundle");

pimcore.plugin.ImportBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.ImportBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        // alert("ImportBundle ready!");
    }
});

var ImportBundlePlugin = new pimcore.plugin.ImportBundle();
