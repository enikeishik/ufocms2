$("document").ready(function(){
    getData();
});

var remainingTime = null;

function getData()
{
    $.ajax({
        url: location.href.substr(0, location.href.length - location.search.length) + '?type=xhr&t=' + (new Date().getTime()),
        type: "GET"
    }).done(function(data) {
        var html = '';
        if (0 == data.IsClosed) {
            html += '<div class="pricestart">Стартовая цена: ' + data.PriceStart + ' руб.</div>';
            html += '<div class="step">Шаг цены: ' + (0 < data.Step ? '+' : '') + data.Step + ' руб.</div>';
            html += '<div class="steptime">Время шага: ' + data.StepTime + ' сек.</div>';
            html += '<div class="pricecurrent">Текущая цена: ' + data.PriceCurrent + ' руб.</div>';
            if (0 != data.IsStarted) {
                if (currentUserId != data.UserId) {
                    html += '<div class="makestep"><a href="?action=step&t=' + (new Date().getTime()) + '" target="_top">Сделать ставку</a></div>';
                } else {
                    html += '<div class="makestep"><span>Последняя ставка сделана Вами</span></div>';
                }
                //<div class="remaining">Если ставок не будет в течении <span><?=$dtm->format($dtmFormat)?></span>, аукцион будет завершен.</div>
                setTimeout('getData()', 9999);
            } else {
                //<div class="notstarted">Аукцион еще не начат, начало аукциона <?=date('d.m.Y H:i', strtotime($item['DateStart']))?></div>
            }
            if (null == remainingTime) {
                setInterval('document.getElementById("updatetime").innerHTML = --remainingTime', 999);
            }
            remainingTime = data.UpdateTimeout;
            html += '<div class="updatetime">До обновления информации осталось <span id="updatetime">' + data.UpdateTimeout + '</span> сек.</div>';
        } else {
            html += '<div class="pricestart">Стартовая цена: ' + data.PriceStart + ' руб.</div>';
            html += '<div class="pricecurrent">Конечная цена: ' + data.PriceCurrent + ' руб.</div>';
            if (currentUserId == data.UserId) {
                html += '<div class="makestep"><span>Последняя ставка сделана Вами</span></div>';
            }
        }
        $("#informer").html(html);
    }).fail(function() { alert(errMessage); });
}
