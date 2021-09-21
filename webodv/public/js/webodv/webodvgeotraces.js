$(document).ready(function() {
    //console.log("hallo")
    $("#treeview").hummingbird("expandNode",{attr:"data-id",name: "GEOTRACES",expandParents:false});
    $("#treeview").hummingbird("expandNode",{attr:"data-id",name: "CURRENT_VERSION",expandParents:false});
});
