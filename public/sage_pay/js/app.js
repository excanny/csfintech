var cardSectionSelector = document.querySelector('#card-section');
var cardSectionID = document.querySelector('#card-section');
var selectionSection = document.querySelector('#paymet-selection');

$('#tabs-container').hide();

// payment method selection
$(".selection__option").click(function(){
    let selected = $(this).attr("data-list"),
        identifier = 'ul li#' + selected + '-tab'

    $(identifier).addClass('is-active')

    selectionSection.style.display= "none";
    $("."+selected).addClass("make-visible");
    $("#tabs-container").addClass("showTabs");
});

// tab logic
$(".tabs__icons-icon").click(function(){
    let self = this;
    let clickedTabAttr = self.getAttribute("data-tab");
    let targetTab = `div#${clickedTabAttr}`;
    let section = document.querySelector(".make-visible");

    $("ul li").removeClass("is-active");
    $(this).addClass("is-active");

    $(".section").removeClass("make-visible");
    $(targetTab).addClass("make-visible");
});

// succesful transaction
function showSuccess(){
    // $(".section").removeClass("make-visible");
    $("#trans-successful").addClass("make-visible");
}

function cc_format(element) {
    let v = element.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    element.value = v;
    let matches = v.match(/\d{4,16}/g);
    let match = matches && matches[0] || '';
    let parts = [];

    for (let i=0, len=match.length; i<len; i+=4) {
        parts.push(match.substring(i, i+4));
    }

    if (parts.length) {
        element.value = parts.join(' ');
    } else {
        return element.value;
    }
}
