/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

jQ(document).ready(function () {
    jQ("#menu-toggle").click(function (e) {
        e.preventDefault();
        jQ("#wrapper").toggleClass("toggled");
    });

    var chartPageViews = document.getElementById('chartPageViews');
    if (chartPageViews) {
        WBHelper.ajax({
            url: '/admin/page/chart',
            type: 'get',
            success: function (data, textStatus, jqXHR) {
                jQ('#totalActiveUsers').text(data.data.total_active_user);
                jQ('#totalInActiveUsers').text(data.data.total_inactive_user);
                jQ('#totalSubscriber').text(data.data.total_subscriber);

                var ctx = chartPageViews.getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.data.labels,
                        datasets: [{
                            label: data.data.label,
                            backgroundColor: 'rgb(1, 74, 84)',
                            data: data.data.data,
                            borderWidth: 0
                        }]
                    }
                });
            }
        });
    }
});