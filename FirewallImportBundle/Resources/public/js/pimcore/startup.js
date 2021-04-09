pimcore.registerNS("pimcore.plugin.FirewallImportBundle");

pimcore.plugin.FirewallImportBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.FirewallImportBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        // alert("FirewallImportBundle ready!");
    }
});

var FirewallImportBundlePlugin = new pimcore.plugin.FirewallImportBundle();
