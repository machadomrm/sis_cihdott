let snowCanvas = function (obj) {
    var canvas = obj.el;

    canvas.style.backgroundColor = obj.background;
    var fillStyle = obj.snowColor;
    var ctx = canvas.getContext("2d");

    var maxSpeed = obj.maxSpeed,
    minSpeed = obj.minSpeed,
    count = obj.amount ,
    rMax = obj.rMax,
    rMin = obj.rMin,
    W, H; //height and width of canvas;
    setHeightWidth( );

    function setHeightWidth( ) {
        W = obj.width;
        H = obj.height;
        canvas.width = W;
        canvas.height = H;
    }

    window.onresize = setHeightWidth;

    var snowGroup = [];
    var i;
    for (i = 0; i < count; i++) {
        snowGroup.push(initialEverySnow());
    }

    function initialEverySnow() {
        return {
        x: Math.random() * W - rMax,
        y: Math.random() * H - rMax,
        r: Math.random() * (rMax - rMin) + rMin,
        s: Math.random() * (maxSpeed - minSpeed) + minSpeed,
        xChangeRate: Math.random() * 1.6 - 0.8
        };
    }

    function draw() {
        ctx.clearRect(0, 0, W, H);
        ctx.beginPath();

        var p;
        for (var i = 0; i < snowGroup.length; i++) {
            p = snowGroup[i];
            ctx.fillStyle = fillStyle;
            ctx.moveTo(p.x, p.y);
            ctx.arc(p.x, p.y, p.r, 0, 2 * Math.PI);
        }
        ctx.fill();
        update();
    }

    var delta = 0;
    function update() {
        //update position of every snow
        delta += 0.01;
        var p;
        for (var i = 0; i < snowGroup.length; i++) {
            p = snowGroup[i];
            p.y += p.s;
            p.x += Math.sin(delta + p.xChangeRate) * p.xChangeRate;
            if (p.x > W + p.r || p.y > H + p.r || p.x < -p.r) {
                snowGroup[i] = initialEverySnow();
                var randomStartPostion = Math.ceil(Math.random() * 3);
                switch (randomStartPostion) {
                    case 1:
                    //drop from top
                    snowGroup[i].x = Math.random() * W;
                    snowGroup[i].y = -rMax;
                    break;
                    case 2:
                    //start from left
                    snowGroup[i].x = -rMax;
                    snowGroup[i].y = Math.random() * H;
                    break;
                    case 3:
                    //start from right
                    snowGroup[i].x = W + rMax;
                    snowGroup[i].y = Math.random() * H;
                    break;
                }
            }
        }
    }
    setInterval(draw, 1000 / 60);
};

if (document.getElementById("snowCanvas")) {
    window.onload = snowCanvas({
        el: document.getElementById("snowCanvas"), // elemento selecionado
        snowColor: "#821C87", // cor dos flocos
        background: "rgba(0,0,0,0)", // cor de fundo que deixei trasnparente para apresentar a minha imagem
        maxSpeed: 2, // velocidade máxima do movimento
        minSpeed: 1, // velocidade mínima do movimento
        width: "", // largura opicional
        height: "", // altura opicional
        amount: 15, // quantidade de flocos
        rMax: 4, // raio máximo do floco
        rMin: 1 // raio mínimo do floco
    });
}