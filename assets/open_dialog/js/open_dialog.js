/*jslint vars: true, plusplus: true, devel: true, nomen: true, indent: 4, maxerr: 50 */
/*global $, document, window, navigator, NAT, jQuery, is_dir_search */

var OpenDlg = {};
/**
 *@todo in the file view show the dirs to
 *@todo sort files by name
 */
OpenDlg.tree_node_id = 1;

OpenDlg.getDirFiles = function (path, callback) {
    "use strict";
    NAT.ajaxCall('read_dir_files', [path], function (data) {
        if (!data.data) {
            console.log("Error#" + data.error);
            return;
        }
        callback(data.data);
    });
};

OpenDlg.getDirDirs = function (path, callback) {
    "use strict";
    NAT.ajaxCall('read_dir_dirs', [path], function (data) {
        if (!data.data) {
            console.log("Error#" + data.error);
            return;
        }
        callback(data.data);
    });

};

OpenDlg.getTopDirs = function (jsTreeCallback) {
    "use strict";
    NAT.ajaxCall('get_top_dirs', [], function (data) {
        if (!data.data) {
            jsTreeCallback({});
            return;
        }
        var dirs = data.data;
        var entries, jsonTree;
        entries = OpenDlg._convertsPathsToEntries(dirs);
        jsonTree = OpenDlg._convertEntriesToJSON(entries);
        jsTreeCallback(jsonTree);

    });

};

OpenDlg._convertEntriesToJSON = function (entries) {
    "use strict";
    var jsonEntryList = [],
    entry,
    entryI;

    for (entryI = 0; entryI < entries.length; entryI++) {
        entry = entries[entryI];
        var jsonEntry = {
            data: entry.name,
            attr: {
                id: "tree_node" + OpenDlg.tree_node_id++
            },
            state : "closed",
            metadata: {
                entry: entry
            }
        };

        jsonEntryList.push(jsonEntry);
    }
    return jsonEntryList;
};

OpenDlg._convertsPathsToEntries = function (paths) {
    "use strict";
    var entryList = [],
    path,
    entryI;
    for (entryI = 0; entryI < paths.length; entryI++) {
        var entry = {},
        bas_name;
        path = paths[entryI];
        /**
         * @todo corract the json encode and remove this
         * Patch for the no utf8 path that return null
         */
        if (path) {
            entry.name =
            entry.fullPath = path;
            bas_name = path.match("[^\/]*$")[0];
            if (bas_name && bas_name !== "") {
                entry.name = bas_name;
            }
            entryList.push(entry);
        }
    }
    return entryList;
};
/**
 * @todo show empty trees as leaf the fris time
 */
OpenDlg.treeDataProvider = function (treeNode, jsTreeCallback) {
    "use strict";
    var dirEntry, isProjectRoot = false;
    if (treeNode === -1) {
        // Special case: root of tree
        OpenDlg.getTopDirs(jsTreeCallback);
        return;
    } else {
        // All other nodes: the DirectoryEntry is saved as jQ data in the tree (by _convertEntriesToJSON())
        dirEntry = treeNode.data("entry");
        OpenDlg.getDirDirs(dirEntry.fullPath, function (paths) {
            var entries = OpenDlg._convertsPathsToEntries(paths);
            var subtreeJSON = OpenDlg._convertEntriesToJSON(entries);
            var emptyDirectory = (subtreeJSON.length === 0);
            if (emptyDirectory) {
                treeNode.removeClass("jstree-closed jstree-open")
                .addClass("jstree-leaf");
            }
            jsTreeCallback(subtreeJSON);

        }, jsTreeCallback);

    }
};

OpenDlg.CreateTree = function () {
    "use strict";
    $("#directory_tree").jstree(
    {
        plugins : ["ui", "themes", "json_data", "crrm", "sort"],
        json_data : {
            data: OpenDlg.treeDataProvider,
            correct_state: false
        },
        core : {
            animation: 0
        },
        themes : {
            //theme: "apple",
            dots: false
        },
        strings : {
            loading : "Loading ...",
            new_node : "New node"
        }
    }
    ).bind("select_node.jstree", function (e, data) {
        $(this).jstree("toggle_node", data.rslt.obj);
        var entry = data.rslt.obj.data('entry');
        if (is_dir_search) {
            $("#filename").val(entry.name);
        } else {
            OpenDlg.getDirFiles(entry.fullPath, function (files) {
                $("#file_view").empty();
                var list = $("<ul/>"),
                entries = OpenDlg._convertsPathsToEntries(files),
                i;
                list.attr('id', 'file_list');
                for (i = 0; i < entries.length; i++) {
                    var list_element = $("<li/>");
                    list_element.text(entries[i].name);
                    list_element.data('name', entries[i].name);
                    list_element.addClass('file');
                    list.append(list_element);
                }
                $("#file_view").append(list);
            });
        }
        $("#filename").data("path", entry.fullPath);
    });
};

$(function () {
    "use strict";
    $("#filename").val("");
    OpenDlg.CreateTree();
    $("#btn_select").click(function () {
        var file;
        file = $("#filename").data('path');
        if (!file) {
            return;
        }
        if (!is_dir_search) {
            var filename = $("#filename").data('name');
            if (!filename) {
                return;
            }
            file += '/' + filename;
        }
        var caller;
        caller = window.opener;
        if (!caller) {
            alert("The windows opener not found");
        }
        caller.NAT.call_callback(
            function () {
                OpenDlg.callback(caller.NAT.NO_ERROR, [file]);
                window.close();
            }
            );
    });
    $("#btn_cancel").click(function () {
        window.close();
    });
    $("#file_view").on('click', 'ul > li.file', function () {
        $("#filename").data("name", $(this).data('name'));
        $("#filename").val($(this).data('name'));
    });
    if (!is_dir_search) {
        $("#file_view").on('dblclick', 'ul > li.file', function () {
            $("#btn_select").trigger('click');
        });
    }else{
        $("#main-view").on('dblclick', '#directory_tree > ul > li', function () {
            $("#btn_select").trigger('click');
        });
    }
});