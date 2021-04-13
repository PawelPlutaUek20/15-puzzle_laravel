<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        @media only screen and (max-width: 630px) {
            #container {
                height: 280px !important;
                width: 280px !important;
            }

            #placeholders {
                width: 250px !important;
                height: 250px !important;
                margin-top: 22.5px !important;
                margin-left: 22.5px !important;
            }

            .tile {
                width: 50px !important;
                height: 50px !important;
                margin-top: 22.5px !important;
                margin-left: 22.5px !important;
            }

            .placeholder {
                width: 50px !important;
                height: 50px !important;
            }

            #time {
                margin-top: 5vh;
            }
        }

        #container {
            position: relative;
            align-items: center;
            justify-items: center;
            background-color: rgb(167, 202, 174);
            border-radius: 10px;
            width: 450px;
            height: 450px;
            margin-top: 10vh;
        }

        #placeholders {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(4, 1fr);
            width: 400px;
            height: 400px;
            margin-top: 35px;
            margin-left: 35px;
        }

        .tile {
            margin-top: 35px;
            margin-left: 35px;
            align-items: center;
            justify-items: center;
            position: absolute;
            width: 80px;
            height: 80px;
            border-radius: 10px;
            transition: top 0.2s linear, left 0.2s linear;

            display: flex;
            justify-content: center;
            font-size: 20pt;

            cursor: grab;

        }

        .tile:not(:last-child) {
            z-index: 2;
            background-color: rgb(255, 255, 168);
        }

        .placeholder {
            align-items: center;
            justify-items: center;
            width: 80px;
            height: 80px;
            border-radius: 10px;
            background-color: rgb(151, 182, 158);
        }

        #main {
            justify-content: center;
            align-items: center;
            display: flex;
            flex-direction: column;

            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        #start,
        #score,
        #leaderboard {
            display: inline-block;
            border-radius: 5px;
            width: 80px;
            height: 40px;
            margin: 10px;
            text-align: center;
            justify-items: center;
            vertical-align: middle;
        }

        #leaderboard {
            width: 40px;
        }

        #time {
            margin-top: 10vh;
            font-size: 50pt;
            font-family: Arial, Helvetica, sans-serif;
        }

        #start,
        #score,
        #leaderboard {
            border: none;
            background-color: rgba(201, 210, 199);
            cursor: pointer;
            color: black;
        }

        span {
            position: relative;
            top: 10px;
        }

        dialog {
            max-width: 80vw;
            min-height: 80px;
            border: 0;
            border-radius: 0.6rem;
            box-shadow: 0 0 1em black;
            z-index: 10;
        }

        .close {
            position: absolute;
            top: 0;
            right: 0;
            padding: 0.3em;
            line-height: 0.6;
            background-color: transparent;
            border: 0;
            font-size: 2em;
        }

        .modal-header {
            margin: 0;
        }

        .modal-body {
            margin-top: -25px;
        }

        .modal-header,
        .modal-body {
            padding: 1em;
        }

        #btnSubmit {
            position: absolute;
            top: 80px;
            right: 115px;
        }
    </style>
</head>

<body>




    <div id="main">

        <div id="time"><span id="counter">0:00.0</span></div>

        <div id="container">
            <div id="placeholders">
                <div class="placeholder"></div>
                <div class="placeholder"></div>
                <div class="placeholder"></div>
                <div class="placeholder"></div>
                <div class="placeholder"></div>
                <div class="placeholder"></div>
                <div class="placeholder"></div>
                <div class="placeholder"></div>
                <div class="placeholder"></div>
                <div class="placeholder"></div>
                <div class="placeholder"></div>
                <div class="placeholder"></div>
                <div class="placeholder"></div>
                <div class="placeholder"></div>
                <div class="placeholder"></div>
                <div class="placeholder"></div>
            </div>

            <div id="tiles">
                <div class="tile">1</div>
                <div class="tile">2</div>
                <div class="tile">3</div>
                <div class="tile">4</div>
                <div class="tile">5</div>
                <div class="tile">6</div>
                <div class="tile">7</div>
                <div class="tile">8</div>
                <div class="tile">9</div>
                <div class="tile">10</div>
                <div class="tile">11</div>
                <div class="tile">12</div>
                <div class="tile">13</div>
                <div class="tile">14</div>
                <div class="tile">15</div>
                <div class="tile"></div>
            </div>
        </div>

        <div id="options">
            <div id="start" onclick="shuffle()"><span>START</span></div>
            <div id="leaderboard" onclick="showDialog(this.id)"><span>??</span></div>
            <div id="score" onclick="showDialog(this.id)"><span>SUBMIT</span></div>
        </div>

        <dialog id="demo-modal">
            <h3 class="modal-header"></h3>
            <div class="modal-body">
                <form id="myForm" method="POST" action="http://v-ie.uek.krakow.pl/~s214325/index.php" onsubmit="return submitForm()">
                    @csrf

                    <label for="name">Name:</label>
                    <input name="name" type="text">
                    <input type="button" value="Submit" id="btnSubmit" onclick="submitForm()">
                </form>
            </div>
            <button onclick="closeDialog(this.id)" id="close-demo-modal" class="close" type="button">&times;</button>
        </dialog>

        <dialog id="leaderboard-modal">
            <h3 class="modal-header"></h3>
            <div class="modal-body">
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Time</th>
                    </tr>
                    @foreach($leaderboard as $player)
                    <tr>
                        <td>{{$player->name}}</td>
                        @php
                        $time = $player->time;
                        $seconds = (int)(($time / 10) % 60);
                        $minutes = (int)(($time / 10) / 60);
                        @endphp

                        <td>{{$minutes}}:{{sprintf("%02d", $seconds)}}.{{$time % 10}}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
            <button onclick="closeDialog(this.id)" id="close-leaderboard-modal" class="close" type="button">&times;</button>
        </dialog>

    </div>

</body>

<script>
    const tiles = document.getElementsByClassName('tile')
    const board = document.getElementById('tiles')
    const timer = document.getElementById('counter')
    const [emptyTile] = [...tiles].filter(el => !el.innerText)
    const demoModal = document.getElementById("demo-modal");
    const leaderboardModal = document.getElementById("leaderboard-modal");
    let interval, started
    let totalSeconds = 0;
    let p = emptyTile.offsetWidth === 50 ? 63 : 100


    let arr = [...tiles].reduce((rows, key, idx) =>
        (idx % 4 == 0 ? rows.push([key]) :
            rows[rows.length - 1].push(key)) && rows, []);


    const resize = () => {
        p = emptyTile.offsetWidth === 50 ? 63 : 100
        render()
    }

    window.onresize = resize

    const render = () => {
        arr.forEach((row, rowIdx) => {
            row.forEach((col, colIdx) => {
                col.style.top = `${rowIdx * p}px`
                col.style.left = `${colIdx * p}px`
            })
        });
    }

    render()

    const swapElements = (el1, el2) => {

        [el1.style.left, el1.style.top, el2.style.left, el2.style.top] = [el2.style.left, el2.style.top, el1.style.left, el1.style.top]

        temp = arr[parseInt(el1.style.top) / p][parseInt(el1.style.left) / p]
        arr[parseInt(el1.style.top) / p][parseInt(el1.style.left) / p] = arr[parseInt(el2.style.top) / p][parseInt(el2.style.left) / p]
        arr[parseInt(el2.style.top) / p][parseInt(el2.style.left) / p] = temp

    }

    const customFindIndexOf = (el) => {
        let row = arr.findIndex(row => row.includes(el));
        let col = arr[row].indexOf(el);
        return [row, col]
    }

    const isLegalMove = (el1, el2) => {

        return Math.abs(el1.offsetLeft - el2.offsetLeft) + Math.abs(el1.offsetTop - el2.offsetTop) === p
    }

    const shuffle = () => {

        timer.innerHTML = "0:00.0"
        started = 1

        clearInterval(interval)
        totalSeconds = 0;

        const getNeighbour = (x) => {
            newPos = x === 3 ? 2 : x === 0 ? 1 : Math.floor(Math.random() * 3 + (x - 1))
            return newPos;
        }

        let i = 0;

        while (i < 1000) {
            i++;

            let [x, y] = customFindIndexOf(emptyTile)

            element = Math.round(Math.random()) ? arr[getNeighbour(x)][y] : arr[x][getNeighbour(y)]

            swapElements(emptyTile, element)
        }

    }

    const validate = () => {

        const invalidPosition = (el, idx) => {
            let actualTilePosition = (parseInt(el.style.left) + 4 * parseInt(el.style.top)) / p
            let originalTilePosition = parseInt(el.innerHTML) - 1

            return idx < 15 && actualTilePosition !== originalTilePosition
        }

        return ![...tiles].some(invalidPosition)

    }

    const setTime = () => {
        ++totalSeconds;
        let seconds = parseInt((totalSeconds / 10) % 60)
        let minutes = parseInt((totalSeconds / 10) / 60)

        timer.innerHTML = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}.${totalSeconds % 10}`
    }

    ['mousedown', 'touchstart'].forEach(evt => {
        board.addEventListener(evt, (event) => {

            if (isLegalMove(event.target, emptyTile)) {

                if (totalSeconds === 0 && started) {
                    interval = setInterval(setTime, 100);
                }

                swapElements(event.target, emptyTile)

                if (validate()) {
                    clearInterval(interval)
                }
            }
        })
    })




    const submitForm = () => {
        if (validate()) {
            const form = document.getElementById("myForm")
            let input = document.createElement("input");
            input.type = "hidden";
            input.name = "time";
            input.id = "result"
            input.value = totalSeconds
            form.appendChild(input)
            form.submit()
        }

    }


    const showDialog = (e) => {
        e === 'score' ? demoModal.showModal() : leaderboardModal.showModal()
    }

    const closeDialog = (e) => {
        e === 'close-demo-modal' ? demoModal.close() : leaderboardModal.close()
    }
</script>

</html>