/**
 * interaction AJAX variables, jQuery required
 */
var sectionId = 0;
var itemId = 0;
var interactionHandler = "/interaction.php?rnd=" + Math.random();
var errCode = 0;
var errMessage = "Ошибка, пожалуйста обновите страницу (Ctrl+F5 или Ctrl+R)";
var errMessages = ['', 
                   'Ошибка, не определен отправитель формы.', 
                   'Ошибка, некорректен отправитель формы.', 
                   'Ошибка, не корректный код с картинки.', 
                   'Операция разрешена только зарегистрированным пользователям сайта.', 
                   'Ваш IP адрес занесен в черный список, операция заблокирована.', 
                   'С Вашего IP адреса недавно была проведена данная операция, попробуйте провести операцию позже.', 
                   'Ошибка, не все обязательные поля формы были заполнены, заполните необходимые поля и попробуйте снова.', 
                   errMessage, 
                   errMessage, 
                   'Ошибка, комментарий не существует или был удален.', 
                   'Вы уже провели данную операцию.'];
var strData = "";
var rateValue = 0;
var commentRateValue = 0;
function setRateValue(v)
{
    rateValue = v;
}
function setCommentRateValue(v)
{
    commentRateValue = v;
}
function getRatingImages(rating)
{
    rating = parseFloat(rating);
    if (isNaN(rating)) {
        rating = 0;
    }
    var i0 = '<img src="/templates/default/images/rate0.png" width="25" height="24" alt="">';
    var i14 = '<img src="/templates/default/images/rate14.png" width="25" height="24" alt="">';
    var i12 = '<img src="/templates/default/images/rate12.png" width="25" height="24" alt="">';
    var i34 = '<img src="/templates/default/images/rate34.png" width="25" height="24" alt="">';
    var i1 = '<img src="/templates/default/images/rate1.png" width="25" height="24" alt="">';
    if (rating < 0.25) {
        return i0 + i0 + i0 + i0 + i0;
    } else if (rating < 0.5) {
        return i14 + i0 + i0 + i0 + i0;
    } else if (rating < 0.75) {
        return i12 + i0 + i0 + i0 + i0;
    } else if (rating < 1) {
        return i34 + i0 + i0 + i0 + i0;
    } else if (rating < 1.25) {
        return i1 + i0 + i0 + i0 + i0;
    } else if (rating < 1.5) {
        return i1 + i14 + i0 + i0 + i0;
    } else if (rating < 1.75) {
        return i1 + i12 + i0 + i0 + i0;
    } else if (rating < 2) {
        return i1 + i34 + i0 + i0 + i0;
    } else if (rating < 2.25) {
        return i1 + i1 + i0 + i0 + i0;
    } else if (rating < 2.5) {
        return i1 + i1 + i14 + i0 + i0;
    } else if (rating < 2.75) {
        return i1 + i1 + i12 + i0 + i0;
    } else if (rating < 3) {
        return i1 + i1 + i34 + i0 + i0;
    } else if (rating < 3.25) {
        return i1 + i1 + i1 + i0 + i0;
    } else if (rating < 3.5) {
        return i1 + i1 + i1 + i14 + i0;
    } else if (rating < 3.75) {
        return i1 + i1 + i1 + i12 + i0;
    } else if (rating < 4) {
        return i1 + i1 + i1 + i34 + i0;
    } else if (rating < 4.25) {
        return i1 + i1 + i1 + i1 + i0;
    } else if (rating < 4.5) {
        return i1 + i1 + i1 + i1 + i14;
    } else if (rating < 4.75) {
        return i1 + i1 + i1 + i1 + i12;
    } else if (rating < 5) {
        return i1 + i1 + i1 + i1 + i34;
    } else {
        return i1 + i1 + i1 + i1 + i1;
    }
}
function sendRatingForm(f)
{
    if (0 == rateValue) {
        alert(errMessage);
        return false;
    }
    $("#rateForm input").attr("disabled", "disabled");
    $("#rateForm").hide("slow");
    $.ajax({
        url: interactionHandler + "&sectionid=" + sectionId + "&itemid=" + itemId,
        type: "POST",
        data: { rate: rateValue }
    }).done(function(data) {
        try {
            eval(data);
        } catch (e) {
            alert(errMessage);
            return false;
        }
        if (0 == errCode) {
            /* DEBUG alert(data); */
            var objData = null;
            try {
                eval(strData);
                var rating = objData.Rating.toString();
                if (-1 != rating.indexOf(",")) {
                    rating = rating.split(",");
                } else if (-1 != rating.indexOf(".")) {
                    rating = rating.split(".");
                } else {
                    rating = [rating, "00"];
                }
                $("#ratingStars").html(getRatingImages(rating[0] + "." + rating[1]));
                $("#ratingValue").html(rating[0] + "," + rating[1].substr(0,2));
                $("#ratesCountValue").html(objData.RatesCnt);
                $("#ratingView").css("background-color", "#ffffff");
                $("#ratingView").css("background-color", "#eeee99");
                $("#ratingView").animate({backgroundColor: "#ffffff"}, "slow");
            } catch (e) {
                alert(errMessage);
            }
        } else if (errCode < 12) {
            alert(errMessages[errCode]);
        } else {
            alert(errMessage);
        }
    }).fail(function() { alert(errMessage); });
    return false;
}
function sendCommentForm(f, captchaKeyField, catpchaValueField)
{
    $("#commentFormSubmit").attr("disabled", "disabled");
    var data = { text: f.elements["text"].value, 
                author: f.elements["author"].value, 
                email: f.elements["email"].value, 
                url: f.elements["url"].value, 
                status: $("input:radio[name=status]:checked") ? $("input:radio[name=status]:checked").val() : 0 };
    data[captchaKeyField] = f.elements[captchaKeyField].value;
    data[catpchaValueField] = f.elements[catpchaValueField].value;
    $.ajax({
        url: interactionHandler + "&sectionid=" + sectionId + "&itemid=" + itemId,
        type: "POST",
        data: data
    }).done(function(data) {
        /* DEBUG alert(data); */
        try {
            eval(data);
        } catch (e) {
            alert(errMessage);
            return false;
        }
        if (0 == errCode) {
            /* DEBUG alert(data); */
            var objData = null;
            try {
                eval(strData);
                var newComment = '<div class="comment">';
                newComment += '<div>' + objData.DateCreate + ' | <b>';
                if ("" != objData.CommentAuthor) {
                    newComment += objData.CommentAuthor + '</b>';
                } else {
                    newComment += '(не представился)</b>';
                }
                if ("" != objData.CommentEmail) {
                    newComment += ' | ' + objData.CommentEmail;
                }
                if ("" != objData.CommentUrl) {
                    newComment += ' | ' + objData.CommentUrl;
                }
                if (-1 == objData.CommentStatus) {
                    newComment += ' | <span style="font-family: \'Courier New\'; padding: 2px; background-color: #ffeeee;">:(</span>';
                } else if (1 == objData.CommentStatus) {
                    newComment += ' | <span style="font-family: \'Courier New\'; padding: 2px; background-color: #eeffee;">:)</span>';
                } else {
                    newComment += ' | <span style="font-family: \'Courier New\'; padding: 2px; background-color: #eeeeee;">:|</span>';
                }
                newComment += ' | IP: ' + objData.IP.substr(0, 6) + '***';
                //comment rate form not need
                newComment += '</div><div>' + objData.CommentText + '</div></div>';
                if ($(".comment").length) {
                    $(".comment").last().after(newComment); //for ASC order
                    //$(".comment").first().before(newComment); //for DESC order
                } else {
                    $("#commentsFormContainer").before('<a name="comments"></a><div class="commentstitle">Комментарии:</div>' + newComment);
                }
                //scroll to comment
                $("#commentsFormContainer").hide("slow");
            } catch (e) {
                /* DEBUG alert(e); */
                alert(errMessage);
            }
        } else if (errCode < 12) {
            alert(errMessages[errCode]);
            $("#commentFormSubmit").removeAttr("disabled");
        } else {
            alert(errMessage);
        }
    }).fail(function() { alert(errMessage); });
    return false;
}
function sendCommentRateForm(f)
{
    var formId = f.id;
    var commentId = f.elements["commentid"].value;
    $("#" + formId + " input").attr("disabled", "disabled");
    $.ajax({
        url: interactionHandler + "&sectionid=" + sectionId + "&itemid=" + itemId,
        type: "POST",
        data: { commentrate: commentRateValue, commentid: commentId }
    }).done(function(data) {
        try {
            eval(data);
        } catch (e) {
            /* DEBUG alert(e); */
            alert(errMessage);
            return false;
        }
        if (0 == errCode) {
            /* DEBUG alert(data); */
            var objData = null;
            try {
                eval(strData);
                $("#commentRatingValue" + commentId).html("<b>" + objData.Rating + "</b> (" + objData.RatesCnt + ")");
                $("#" + formId + " input").hide("slow");
            } catch (e) {
                alert(errMessage);
            }
        } else if (errCode < 12) {
            alert(errMessages[errCode]);
        } else {
            alert(errMessage);
        }
    }).fail(function() { alert(errMessage); });
    return false;
}
