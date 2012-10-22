/*jslint vars: true, plusplus: true, devel: true, nomen: true, indent: 4, maxerr: 50 */
/*global $,document */

$(function () {
    "use strict";
    $(document).on('click', "a[href='#']", function (e) {
        e.preventDefault();
    });
});