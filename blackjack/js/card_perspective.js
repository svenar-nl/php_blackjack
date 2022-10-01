var mousePos = {"x": 0, "y": 0};
var mouseOverInterval = null;
var stopCounter = 0;

$(document).ready(function() {
    $(".3d-perspective").mouseover(function() {
        if (mouseOverInterval != null) {
            return;
        }

        var self = this;
        mouseOverInterval = setInterval(function() {
            var size = {"width": $(self).outerWidth(), "height": $(self).outerHeight()};
            var relativeMousePos = {"x": mousePos.x - $(self).offset().left, "y": mousePos.y - $(self).offset().top};

            var mousePercent = {"x": map(relativeMousePos.x, 0, size.width, 0, 100), "y": map(relativeMousePos.y, 0, size.height, 0, 100)};
            mousePercent.x = mousePercent.x < 0 ? 0 : (mousePercent.x > 100 ? 100 : mousePercent.x);
            mousePercent.y = mousePercent.y < 0 ? 0 : (mousePercent.y > 100 ? 100 : mousePercent.y);

            if (relativeMousePos.x < 0 || relativeMousePos.x > size.width || relativeMousePos.y < 0 || relativeMousePos.y > size.height) {
                if (stopCounter > 10) {
                    $(self).css({"transform": "perspective(600px) rotateX(0deg) rotateY(0deg) scale(1"});
                    $($($(self).children()[0]).children()[0]).css({"transform": "perspective(600px) translateY(0px) translateX(0px)"});
                    clearInterval(mouseOverInterval);
                    mouseOverInterval = null;
                }
                stopCounter += 1;
            } else {
                stopCounter = 0;
                $(self).css({"transform": "perspective(600px) rotateX(" + ((100 - mousePercent.y - 50) / 3) + "deg) rotateY(" + ((mousePercent.x - 50) / 3) + "deg) scale(1)"});
                $($($(self).children()[0]).children()[0]).css({"transform": "perspective(600px) translateY(" + ((-100 + mousePercent.y + 50) / 3) + "px) translateX(" + ((mousePercent.x - 50) / 3) + "px)"});
            }

        }, 1);
    });
}).mousemove(function(event){
    mousePos.x = event.pageX;
    mousePos.y = event.pageY;
});

function map(x, in_min, in_max, out_min, out_max) {
    return (x - in_min) * (out_max - out_min) / (in_max - in_min) + out_min;
}