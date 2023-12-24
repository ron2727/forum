<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        div {
            width: 55%;
            height: 250px;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .one {
            background-image: linear-gradient(43deg, #4158D0 0%, #C850C0 46%, #FFCC70 100%);
        }

        .two {
            background-image: linear-gradient(135deg, #00DBDE 0%, #FC00FF 100%);
        }

        .three {
            background-image: linear-gradient(120deg, #d4fc79 0%, #96e6a1 100%);
        }

        .four {
            background-image: linear-gradient(to top, #30cfd0 0%, #330867 100%);
        }

        .five {
            background-image: linear-gradient(to top, #c471f5 0%, #fa71cd 100%);
        }

        .six {
            background-image: linear-gradient(to top, #fff1eb 0%, #ace0f9 100%);
        }
        .seven { 
            background-image: linear-gradient(108deg, #08AEEA 0%, #2AF598 100%);
        }
        .eight {
            background-image: linear-gradient(60deg, #abecd6 0%, #fbed96 100%);
        }
        .nine {
            background-image: linear-gradient(-60deg, #16a085 0%, #f4d03f 100%);
        }
        .ten {
            background-image: linear-gradient(-225deg, #B6CEE8 0%, #F578DC 100%);
        }
    </style>
</head>

<body>
    <div class="one">
    </div>
    <div class="two">
    </div>
    <div class="three">
    </div>
    <!-- <div class="four">
    </div>
    <div class="five">
    </div>
    <div class="six">
    </div>
    <div class="seven">
    </div>
    <div class="eight">
    </div>
    <div class="nine">
    </div>
    <div class="ten"> -->
    </div>
</body>

<script>
    let currentPos = getStartingLoad()

    document.addEventListener('scroll', function(){
       let currentSize = document.body.scrollTop + document.body.clientHeight;
       let scrollBodyHeight = document.body.scrollHeight;

       if (currentSize == scrollBodyHeight) {
          console.log(currentPos(2))
       }
    })

    function getStartingLoad() {
        let startingPos = 2;

        return function(increment){
            return startingPos+=increment;
        }
    }
</script>
</html>