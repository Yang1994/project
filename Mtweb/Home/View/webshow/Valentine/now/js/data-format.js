function getTableData(theData) {

    var theHtml = "";
    for (var i = 0; i < theData.length; i++) {
        theHtml += "<div class=\"table-content\"><div class=\"name-content\">";
        theHtml += "<img src=\"" + theData[i][0] + "\" />";
        theHtml += "<div class=\"name-text\">";
        theHtml += "<span>" + theData[i][1] + "</span>";
        theHtml += "<span>" + theData[i][2] + "</span>";
        theHtml += "</div>";
        theHtml += "</div>";
        theHtml += getRankingHTML(i);
        theHtml += "<div class=\"number\">";
        theHtml += "<span>X" + theData[i][3] + "</span>";
        theHtml += "</div>";
        theHtml += getCashCountHTML(i);
        theHtml += "</div>";
    }

    return theHtml;
}

function getRankingHTML(index) {
    var theHtml = "<div class=\"ranking\">";
    if (index == 0) {
        theHtml += "<img src=\"/Public/Home/images/valentine/01.png\"  alt=\"\" />";
    } else if (index == 1) {
        theHtml += "<img src=\"/Public/Home/images/valentine/02.png\"  alt=\"\" />";
    } else if (index == 2) {
        theHtml += "<img src=\"/Public/Home/images/valentine/03.png\"  alt=\"\" />";
    } else {
        theHtml += "<span>" + (index + 1) + "</span>";
    }

    theHtml += "</div>";
    return theHtml;
}

function getCashCountHTML(index) {
    var theHtml = "<div class=\"reward\">";
    var count = 0;
    if (index == 0) {
        count = 600;
        theHtml += "<span>" + count + "现金</span>";
        theHtml += "<span>热玩置顶6H</span>";
    } else if (index == 1) {
        count = 400;
        theHtml += "<span>" + count + "现金</span>";
        theHtml += "<span>热玩置顶4H</span>";
    } else if (index == 2) {
        count = 200;
        theHtml += "<span>" + count + "现金</span>";
        theHtml += "<span>热玩置顶2H</span>";
    } else if (index == 3) {
        count = 100;
        theHtml += "<span class=\"cash_number\">" + count + "现金</span>";
    } else if (index == 4) {
        count = 50;
        theHtml += "<span class=\"cash_number\">" + count + "现金</span>";
    } else {
        count = 25;
        theHtml += "<span class=\"cash_number\">" + count + "现金</span>";
    }

    theHtml += "</div>";

    return theHtml;
}