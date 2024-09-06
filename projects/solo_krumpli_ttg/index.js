const elements = [
    {
        time: 2,
        type: 'water',
        shape: [[1,1,1],
                [0,0,0],
                [0,0,0]],
        rotation: 0,
        mirrored: false
    },
    {
        time: 2,
        type: 'town',
        shape: [[1,1,1],
                [0,0,0],
                [0,0,0]],
        rotation: 0,
        mirrored: false        
    },
    {
        time: 1,
        type: 'forest',
        shape: [[1,1,0],
                [0,1,1],
                [0,0,0]],
        rotation: 0,
        mirrored: false  
    },
    {
        time: 2,
        type: 'farm',
        shape: [[1,1,1],
                [0,0,1],
                [0,0,0]],
            rotation: 0,
            mirrored: false  
        },
    {
        time: 2,
        type: 'forest',
        shape: [[1,1,1],
                [0,0,1],
                [0,0,0]],
        rotation: 0,
        mirrored: false  
    },
    {
        time: 2,
        type: 'town',
        shape: [[1,1,1],
                [0,1,0],
                [0,0,0]],
        rotation: 0,
        mirrored: false  
    },
    {
        time: 2,
        type: 'farm',
        shape: [[1,1,1],
                [0,1,0],
                [0,0,0]],
        rotation: 0,
        mirrored: false  
    },
    {
        time: 1,
        type: 'town',
        shape: [[1,1,0],
                [1,0,0],
                [0,0,0]],
        rotation: 0,
        mirrored: false  
    },
    {
        time: 1,
        type: 'town',
        shape: [[1,1,1],
                [1,1,0],
                [0,0,0]],
        rotation: 0,
        mirrored: false  
    },
    {
        time: 1,
        type: 'farm',
        shape: [[1,1,0],
                [0,1,1],
                [0,0,0]],
        rotation: 0,
        mirrored: false  
    },
    {
        time: 1,
        type: 'farm',
        shape: [[0,1,0],
                [1,1,1],
                [0,1,0]],
        rotation: 0,
        mirrored: false  
    },
    {
        time: 2,
        type: 'water',
        shape: [[1,1,1],
                [1,0,0],
                [1,0,0]],
        rotation: 0,
        mirrored: false  
    },
    {
        time: 2,
        type: 'water',
        shape: [[1,0,0],
                [1,1,1],
                [1,0,0]],
        rotation: 0,
        mirrored: false  
    },
    {
        time: 2,
        type: 'forest',
        shape: [[1,1,0],
                [0,1,1],
                [0,0,1]],
        rotation: 0,
        mirrored: false  
    },
    {
        time: 2,
        type: 'forest',
        shape: [[1,1,0],
                [0,1,1],
                [0,0,0]],
        rotation: 0,
        mirrored: false  
    },
    {
        time: 2,
        type: 'water',
        shape: [[1,1,0],
                [1,1,0],
                [0,0,0]],
        rotation: 0,
        mirrored: false  
    },
]
var GAME_TABLE = 
[
[0,0,0,0,0,0,0,0,0,0,0],
[0,1,0,0,0,0,0,0,0,0,0],
[0,0,0,0,0,0,0,0,0,0,0],
[0,0,0,0,0,0,0,0,1,0,0],
[0,0,0,0,0,0,0,0,0,0,0],
[0,0,0,1,0,0,0,0,0,0,0],
[0,0,0,0,0,0,0,0,0,0,0],
[0,0,0,0,0,0,0,0,0,0,0],
[0,0,0,0,0,0,0,0,0,1,0],
[0,0,0,0,0,1,0,0,0,0,0],
[0,0,0,0,0,0,0,0,0,0,0]
]
var TIME = 0 
var CURRENT_SEASON=0
var TOTAL_TIME=0
var SEASON_POINTS = [0,0,0,0] // indexenként: tavasz,nyár,ősz,tél pontjait tárolja
var QUEST_POINTS = [0,0,0,0,0,0,0,0] // tavasz 1. kuldetes, tavasz 2. kuldetes, nyar 1. kuldetes, nyar 2. kuldetes, stb... 
let GAME_ELEMENTS = shuffle(elements) 
var GAME_QUESTS=[]
var GAME_ENDED=false
var DISCOVERED_MOUNTAINS=[]
var MOUNTAINS_PER_SEASON=[0,0,0,0]
function shuffle(inarr) {
    let array = [...inarr]
    let currentIndex = array.length,  randomIndex;
    while (currentIndex > 0) {
      randomIndex = Math.floor(Math.random() * currentIndex);
      currentIndex--;
      [array[currentIndex], array[randomIndex]] = [
        array[randomIndex], array[currentIndex]];
    }
    return array;
}

function shuffleQuests(){
    while(GAME_QUESTS.length!=4){
        randomQuest=Math.round(Math.random()*11)
        if(!GAME_QUESTS.includes(randomQuest)){
            GAME_QUESTS.push(randomQuest)
        }
    } 
}
/*
0 - üres - nem lehetseges lerakni
1 - hegy - nem lehetseges lerakni
2 - erdő
3 - falu
4 - farm
5 - víz
*/
//#region elementManipulation
function getElementType(element){
    switch (element.type) {
        case "forest":
            return 2;
            break;
        case "town":
            return 3;
            break;
        case "farm":
            return 4;
            break;
        case "water":
            return 5;
            break;
        default:
            break;
    }

}
function rotateElement()
{
    for (let x = 0; x < 3 / 2; x++) 
    {
        for (let y = x; y < 2 - x; y++)
        {      
        let temp = GAME_ELEMENTS[0].shape[x][y];
        GAME_ELEMENTS[0].shape[x][y] = GAME_ELEMENTS[0].shape[y][2 - x];
        GAME_ELEMENTS[0].shape[y][2 - x]= GAME_ELEMENTS[0].shape[2 - x][2 - y];
        GAME_ELEMENTS[0].shape[2 - x][2 - y] = GAME_ELEMENTS[0].shape[2 - y][x];
        GAME_ELEMENTS[0].shape[2 - y][x] = temp;
        }
    }
    renderElementTable()
}
function mirrorElement() {
    const mirroredMatrix = new Array(3);
    for (let i = 0; i < 3; i++) {
      mirroredMatrix[i] = GAME_ELEMENTS[0].shape[i].slice().reverse();
    }
    GAME_ELEMENTS[0].shape = mirroredMatrix
    renderElementTable()
}
function placeElement(x,y){ //x,y a bal felső 
    shouldPlace=true
    stayInsideTableWarningThrown=false
    obstructions=[]
    if(y>9 || x<1 || x>9 || y<1){
        alert("Kérlek próbáld meg az elemet a táblán belül lerakni!")
    }
    else{
    for (let i = 0; i < 3; i++) {
        for (let j = 0; j < 3; j++) {
            if(GAME_ELEMENTS[0].shape[i][2-j]!=0 && GAME_TABLE[y-1+i][x+1-j]!=0) //ha a ket elem kozul barmelyik csempen is lesz egyezes akkor hibat fog dobni
            {   
                if(GAME_TABLE[y-1+i][x+1-j]!=undefined){
                obstructions.push([x+1-j,y-1+i])
                }
                shouldPlace=false
            }
        }
    }
    if(shouldPlace){
        elementtype = getElementType(GAME_ELEMENTS[0])
        for (let i = 0; i < 3; i++) {
            for (let j = 0; j < 3; j++) {
                if(GAME_ELEMENTS[0].shape[i][2-j]!=0){
                    GAME_TABLE[y-1+i][x+1-j] = elementtype
                }
            }
        }
        //idő változása
        evaluateSeason()
        TIME += GAME_ELEMENTS[0].time
        if(TIME>=7){
            TIME=0  
            CURRENT_SEASON+=1
        }
        TOTAL_TIME=TIME+CURRENT_SEASON*7
        

        GAME_ELEMENTS.shift()
        if(GAME_ELEMENTS.length==0){
            GAME_ELEMENTS=shuffle(elements) //feltetelezem hogy amikor elfogynak a tajegysegek akkor ujra kell oket keverni, mert akarhogy is szamoltam, a tajegysegek osszesitett ideje mindig 27 volt, a jatek viszont akkor all le ha az ido >28
        }
        if(TOTAL_TIME>=28){
            renderGameTable()
            renderElementTable()
            finalpoints = SEASON_POINTS[0]+SEASON_POINTS[1]+SEASON_POINTS[2]+SEASON_POINTS[3]

            GAME_ENDED=true
        }
    }
    updateInfoDisplay()
    renderGameTable()
    renderElementTable()
    if(!shouldPlace){
        obstructions.forEach(obs => {
            warnObstruction(obs[0],obs[1])            
        });
    }
    }
}
//#endregion

function warnObstruction(x,y){
    const rows = table.getElementsByTagName('tr');

        const cells = rows[y].getElementsByTagName('td');
        cells[x].style.border = "solid red 3px";

}
function tileOnEdge(x,y){
    if(GAME_TABLE[x+1]==undefined || GAME_TABLE[x-1]==undefined || GAME_TABLE[x][y+1]==undefined || GAME_TABLE[x][y-1]==undefined){
        return true
    }
    return false
}

//#region quests 
function Quest_EdgeOfForest(){
    //Az erdő széle: A térképed szélével szomszédos erdőmezőidért egy-egy pontot kapsz.
    sum=0
    for (let i = 0; i < 11; i++) {
        for (let j = 0; j < 11; j++) {
            if(tileOnEdge(i,j) && GAME_TABLE[i][j]==2){
                sum+=1
            }
        }
    }
    return sum
}
function Quest_DreamyValley(){
    //Álmos-Völgy: "Minden olyan sorért, amelyben három erdőmező van, négy-négy pontot kapsz."
    sum=0
    for (let i = 0; i < 11; i++) {
        numOfTreesInLine = 0
        for (let j = 0; j < 11; j++) {
            if(GAME_TABLE[i][j]==2){
                numOfTreesInLine+=1
            }
        }
        if(numOfTreesInLine==3){
            sum+=4
        }
        
    }
    return sum
}
function Quest_PotatoWatering(){
    //Krumpliöntözés: "A farmmezőiddel szomszédos vízmezőidért két-két pontot kapsz."
    sum=0
    for (let i = 0; i < 11; i++) {
        for (let j = 0; j < 11; j++) {
            if(GAME_TABLE[i][j]==4){
                if(i!=10){
                    if(GAME_TABLE[i+1][j]==5){
                        sum+=2
                    }
                }
                if(i!=0){
                    if(GAME_TABLE[i-1][j]==5){
                        sum+=2
                    }
                }
                if(GAME_TABLE[i][j+1]==5){
                    sum+=2
                }
                if(GAME_TABLE[i][j-1]==5){
                    sum+=2
                }
            }   
        }
    }
    return sum
}
function Quest_BorderLands(){
    //Határvidék: "Minden teli sorért vagy oszlopért 6-6 pontot kapsz."
    sum=0
    for (let i = 0; i < 11; i++) {

        fullTilesInColumn=0
        fullTilesInRow=0
        for (let j = 0; j < 11; j++) {
            if(GAME_TABLE[i][j]!=0){
                fullTilesInRow+=1
            }
            if(GAME_TABLE[j][i]!=0){
                fullTilesInColumn+=1
            }
        }
        if(fullTilesInColumn==11){
            sum+=6
        }
        if(fullTilesInRow==11){
            sum+=6
        }
    }
    return sum
}
function Quest_TreeLine(){
    //Fasor: "A leghosszabb, függőlegesen megszakítás nélkül egybefüggő erdőmezők mindegyikéért kettő-kettő pontot kapsz. Két azonos hosszúságú esetén csak az egyikért."
    maxLength=0
    for (let i = 0; i < 11; i++) {
        treesInColumn=0
        for (let j = 0; j < 11; j++) {
            if(GAME_TABLE[j][i]==2){
                treesInColumn+=1
            }
            else{
                if(maxLength<treesInColumn){
                    maxLength=treesInColumn
                }
                treesInColumn=0
            }
        }
        if(maxLength<treesInColumn){
            maxLength=treesInColumn
        }
        
    }
    return maxLength*2

}
function Quest_RichCity(){
    //Gazdag város: "A legalább három különböző tereptípussal szomszédos falurégióidért három-három pontot kapsz."
    sum=0
    for (let i = 0; i < 11; i++) {
        for (let j = 0; j < 11; j++) {
            if(GAME_TABLE[i][j]==3){
                adjecencies=[]
                if(!adjecencies.includes(GAME_TABLE[i+1][j]) && GAME_TABLE[i+1][j]!=undefined && GAME_TABLE[i+1][j]!=0){
                    adjecencies.push(GAME_TABLE[i+1][j])
                }
                if(i!=0){
                    if(!adjecencies.includes(GAME_TABLE[i-1][j]) && GAME_TABLE[i-1][j]!=undefined && GAME_TABLE[i-1][j]!=0){
                        adjecencies.push(GAME_TABLE[i-1][j])
                    }
                }   
                if(!adjecencies.includes(GAME_TABLE[i][j+1]) && GAME_TABLE[i][j+1]!=undefined && GAME_TABLE[i][j+1]!=0){
                    adjecencies.push(GAME_TABLE[i][j+1])
                }
                if(!adjecencies.includes(GAME_TABLE[i][j-1]) && GAME_TABLE[i][j-1]!=undefined && GAME_TABLE[i][j-1]!=0){
                    adjecencies.push(GAME_TABLE[i][j-1])
                }
                if(adjecencies.length>=3){
                    sum+=3
                }

            }
        }
    }
    return sum
}
function Quest_IrrigationCanal(){
    //Öntözőcsatorna: "Minden olyan oszlopodért, amelyben a farm illetve a vízmezők száma megegyezik, négy-négy pontot kapsz. Mindkét tereptípusból legalább egy-egy mezőnek lennie kell az oszlopban ahhoz, hogy pontot kaphass érte."
    sum=0
    for (let i = 0; i < 11; i++) {
        numOfFarms=0
        numOfWater=0
        for (let j = 0; j < 11; j++) {
            if(GAME_TABLE[j][i]==4){
                numOfFarms+=1
            }
            if(GAME_TABLE[j][i]==5){
                numOfWater+=1
            }
        }
        if(numOfFarms==numOfWater && numOfFarms>0){
            sum+=4
        }
    }
    return sum
}
function Quest_ValleyOfMages(){
    //Mágusok Völgye: "A hegymezőiddel szomszédos vízmezőidért három-három pontot kapsz."
    sum=0
    for (let i = 0; i < 11; i++) {
        for (let j = 0; j < 11; j++) {
            if(GAME_TABLE[i][j]==1){
                if(i!=10){
                    if(GAME_TABLE[i+1][j]==5){
                        sum+=3
                    }
                }
                if(i!=0){
                    if(GAME_TABLE[i-1][j]==5){
                        sum+=3
                    }
                }
                if(GAME_TABLE[i][j+1]==5){
                    sum+=3
                }
                if(GAME_TABLE[i][j-1]==5){
                    sum+=3
                }
            }   
        }
    }
    return sum
}
function Quest_EmptyPlot(){
    //Üres Telek: "A városmezőiddel szomszédos üres mezőkért 2-2 pontot kapsz."
    sum=0
    for (let i = 0; i < 11; i++) {
        for (let j = 0; j < 11; j++) {
            if(GAME_TABLE[i][j]==3){
                if(i!=10){
                    if(GAME_TABLE[i+1][j]==0){
                        sum+=2
                    }
                }
                if(i!=0){
                    if(GAME_TABLE[i-1][j]==0){
                        sum+=2
                    }
                }
                if(GAME_TABLE[i][j+1]==0){
                    sum+=2
                }
                if(GAME_TABLE[i][j-1]==0){
                    sum+=2
                }
            }   
        }
    }
    return sum
}
function Quest_LineHouse(){
    //Sorház: "A leghosszabb, vízszintesen megszakítás nélkül egybefüggő falumezők mindegyikéért kettő-kettő pontot kapsz."
    maxLength=0
    numberOfMaxRows=0
    townsInRow=0
    for (let i = 0; i < 11; i++) {
        townsInRow=0
        for (let j = 0; j < 12; j++) {
            if(GAME_TABLE[i][j]==3){
                townsInRow+=1
            }
            else{
                if(maxLength<townsInRow){
                    maxLength=townsInRow
                    numberOfMaxRows=1
                }
                else if(maxLength==townsInRow){
                    numberOfMaxRows+=1
                }
                townsInRow=0
            }
        }
    }
    return maxLength*2*numberOfMaxRows
}
function Quest_OddSilos(){
    //Páratlan Silók: "Minden páratlan sorszámú teli oszlopodért 10-10 pontot kapsz."
    sum=0
    for (let i = 0; i < 11; i++) {
        fullCells=0
        if(i+1%2==1){
            for (let j = 0; j < 11; j++) {
                if(GAME_TABLE[j][i]!=0){
                    fullCells+=1
                }
            }
            if(fullCells==11){
                sum+=10
            }
        }
    }
    return sum
    
}
function Quest_RichRegion(){
    //Gazdag Vidék: "Minden legalább öt különböző tereptípust tartalmazó sorért négy-négy pontot kapsz."
    sum=0
    for (let i = 0; i < 11; i++) {
        tilesInRow=[]
        for (let j = 0; j < 11; j++) {
            if(!tilesInRow.includes(GAME_TABLE[i][j]) && GAME_TABLE[i][j]!=0){
                tilesInRow.push(GAME_TABLE[i][j])
            }
        }
        if(tilesInRow.length>=5){
            sum+=4
        }
    }
    return sum
}
function Quest_SurroundedMountains(){
    for (let i = 0; i < 11; i++) {
        for (let j = 0; j < 11; j++) {
            if(GAME_TABLE[i][j]==1){
                if(GAME_TABLE[i+1][j]!=0 && GAME_TABLE[i-1][j]!=0 && GAME_TABLE[i][j+1]!=0 && GAME_TABLE[i][j-1]!=0 && !DISCOVERED_MOUNTAINS.includes(i)){
                    DISCOVERED_MOUNTAINS.push(i)
                    MOUNTAINS_PER_SEASON[CURRENT_SEASON]+=1
                }
            }
        }
    }
}
//#endregion

function evaluateSeason(){
    quest1num=CURRENT_SEASON%4 //igy telen (tehat amikor a CURRENT_SEASON=3 3-as es a 0-s indexu kuldeteseket kapjuk)
    quest2num=(CURRENT_SEASON+1)%4

    QUEST_POINTS[CURRENT_SEASON*2]=executeQuestByID(GAME_QUESTS[quest1num]) //kiszamolja az elso kuldetes pontjait
    QUEST_POINTS[(CURRENT_SEASON*2)+1]=executeQuestByID(GAME_QUESTS[quest2num]) //kiszamolja a masodik kuldetes altal adott pontokat
    Quest_SurroundedMountains()
    SEASON_POINTS[CURRENT_SEASON]=QUEST_POINTS[CURRENT_SEASON*2]+QUEST_POINTS[(CURRENT_SEASON*2)+1]+MOUNTAINS_PER_SEASON[CURRENT_SEASON] //összeadja a küldetesek pontjait es eltarolja az adott evszaknal
}
function executeQuestByID(questID){
    switch (questID) {
        case 0:
            return Quest_EdgeOfForest();
            break;
        case 1:
            return Quest_DreamyValley();
            break;
        case 2:
            return Quest_PotatoWatering();
            break;
        case 3:
            return Quest_BorderLands();
            break;
        case 4:
            return Quest_TreeLine();
            break;
        case 5:
            return Quest_RichCity();
            break;
        case 6:
            return Quest_IrrigationCanal();
            break;
        case 7:
            return Quest_ValleyOfMages();
            break;
        case 8:
            return Quest_EmptyPlot();
            break;
        case 9:
            return Quest_LineHouse();
            break;
        case 10:
            return Quest_OddSilos();
            break;
        case 11:
            return Quest_RichRegion();
            break;
        default:
            break;
    }
}
function renderGameTable() {
    const gametable = document.getElementById("GAME_TABLE");
    gametable.innerHTML = "";
    GAME_TABLE.forEach(rowData => {
      const row = document.createElement("tr");
  
      rowData.forEach(cellData => {
        const cell = document.createElement("td");
        //cell.innerHTML=cellData
        switch (cellData) {
            case 0:
                cell.classList.add("base")
                break;
            case 1:
                cell.classList.add("mountain")
                break;
            case 2:
                cell.classList.add("forest")
                break;
            case 3:
                cell.classList.add("town")
                break;
            case 4:
                cell.classList.add("farm")
                break;
            case 5:
                cell.classList.add("water")
                break;
            default:
                console.log("unknown tile type");
                break;
        }
        row.appendChild(cell);
      });
  
      gametable.appendChild(row);
    });
}
function renderElementTable(){
    const gametable = document.getElementById("CURRENT_ELEMENT_TABLE");
    gametable.innerHTML = "";
    GAME_ELEMENTS[0].shape.forEach(rowData => {
      const row = document.createElement("tr");
  
      rowData.forEach(cellData => {
        const cell = document.createElement("td");
        //cell.innerHTML=cellData
        switch (cellData*getElementType(GAME_ELEMENTS[0])) { 
            case 0:
                cell.classList.add("base")
                break;
            case 1:
                cell.classList.add("mountain")
                break;
            case 2:
                cell.classList.add("forest")
                break;
            case 3:
                cell.classList.add("town")
                break;
            case 4:
                cell.classList.add("farm")
                break;
            case 5:
                cell.classList.add("water")
                break;
            default:
                console.log("unknown tile type");
                break;
        }
        row.appendChild(cell);
      });
  
      gametable.appendChild(row);
    });
    
    const elementTime=document.getElementById("CURRENT_ELEMENT_TIME")
    elementTime.innerHTML=("🕒"+GAME_ELEMENTS[0].time)
   //🕒
}
function handleGameTableClick(event) { //ITT RAKJA LE AZ ELEMEKET
    const cell = event.target;
    const rowIndex = cell.parentElement.rowIndex;
    const cellIndex = cell.cellIndex;
    if(!GAME_ENDED){
    placeElement(cellIndex,rowIndex) 
    }
    for (let cell of cells) {
        cell.addEventListener('click', handleGameTableClick);
        cell.addEventListener('mouseenter',handleShadowRendering)
        cell.addEventListener('mouseout',handleShadowCleanup)
      }
}
function updateInfoDisplay(){
    const timeDisplay = document.getElementById("timeRemainingInSeason")
    const currentSeasonDisplay=document.getElementById("currentSeasonDisplay")
    switch(CURRENT_SEASON){
        case 0:
            currentSeasonDisplay.innerHTML="Jelenlegi évszak: Tavasz (AB)"
            break;
        case 1:
            currentSeasonDisplay.innerHTML="Jelenlegi évszak: Nyár (BC)"
            break;
        case 2:
            currentSeasonDisplay.innerHTML="Jelenlegi évszak: Ősz (CD)"
            break;
        case 3:
            currentSeasonDisplay.innerHTML="Jelenlegi évszak: Tél (DA)"
            break;
        default:
            finalpoints = SEASON_POINTS[0]+SEASON_POINTS[1]+SEASON_POINTS[2]+SEASON_POINTS[3]
            currentSeasonDisplay.innerHTML= "Vége A Játéknak! Végpontszámod:" + finalpoints
            break;
    }
    timeDisplay.innerHTML= "Évszakból Hátramaradt Idő: "+ (7-TIME)+"/7"

    const springPoints=document.getElementById("springPoints")
    springPoints.innerHTML = `Tavasz: <br>${SEASON_POINTS[0]} pont</div>`
    const summerPoints=document.getElementById("summerPoints")
    summerPoints.innerHTML = `Nyár: <br>${SEASON_POINTS[1]} pont</div>`
    const fallPoints=document.getElementById("fallPoints")
    fallPoints.innerHTML = `Ősz: <br>${SEASON_POINTS[2]} pont</div>`
    const winterPoints=document.getElementById("winterPoints")
    winterPoints.innerHTML = `Tél: <br>${SEASON_POINTS[3]} pont</div>`

    const cells = document.querySelectorAll('.questCell');
    i=0
    cells.forEach((cell) => {
        cell.style.color="white";
        cell.style.backgroundImage = `url('assets/missions/${GAME_QUESTS[i]}.png')`;
        if((i==CURRENT_SEASON || i==CURRENT_SEASON+1 || i==CURRENT_SEASON+1-4) &&CURRENT_SEASON!=4  )
        {
            cell.style.color="yellow";
        }
        i++
    });

    const questAPoints = document.getElementById("questAPoints")
    questAPoints.innerHTML="A: " + (QUEST_POINTS[0]+QUEST_POINTS[7])
    const questBPoints = document.getElementById("questBPoints")
    questBPoints.innerHTML="B: " + (QUEST_POINTS[1]+QUEST_POINTS[2])
    const questCPoints = document.getElementById("questCPoints")
    questCPoints.innerHTML="C: " + (QUEST_POINTS[3]+QUEST_POINTS[4])
    const questDPoints = document.getElementById("questDPoints")
    questDPoints.innerHTML="D: " + (QUEST_POINTS[5]+QUEST_POINTS[6])
}
function renderShadow(x,y){
    const rows = table.getElementsByTagName('tr');
    for (let i = 0; i < 3; i++) {
        for (let j = 0; j < 3; j++) {
            const cells = rows[y-1+i].getElementsByTagName('td');
            if (GAME_ELEMENTS[0].shape[i][j]!=0) {
                cells[x-1+j].classList.add("marked")
                if(GAME_TABLE[y-1+i][x-1+j]!=0){
                    cells[x-1+j].classList.remove("marked")
                    cells[x-1+j].classList.add("markedandoccupied")
                }
            }
        }
    }
}
function shadowCleanup(x,y){
    const rows = table.getElementsByTagName('tr');
    for (let i = 0; i < 3; i++) {
        for (let j = 0; j < 3; j++) {
            const cells = rows[y-1+i].getElementsByTagName('td');
            if (GAME_ELEMENTS[0].shape[i][j]!=0) {
                cells[x-1+j].classList.remove("marked")
                if(GAME_TABLE[y-1+i][x-1+j]!=0){
                    cells[x-1+j].classList.remove("markedandoccupied")
                }
            }
        }
    }
}

function handleShadowRendering(event){
    const shadcell = event.target;
    const shadrowIndex = shadcell.parentElement.rowIndex;
    const shadcellIndex = shadcell.cellIndex;
    if(shadcellIndex>=1 &&shadcellIndex<=9 &&shadrowIndex>=1 &&shadrowIndex<=9){
    renderShadow(shadcellIndex,shadrowIndex)
    } 
}

function handleShadowCleanup(event){
    const clcell = event.target;
    const clrowIndex = clcell.parentElement.rowIndex;
    const clcellIndex = clcell.cellIndex;
    if(clcellIndex>=1&& clcellIndex<=9&& clrowIndex>=1&& clrowIndex<=9){
    shadowCleanup(clcellIndex,clrowIndex) 
    }
}

shuffleQuests()
renderGameTable()
renderElementTable()
updateInfoDisplay()
const table = document.getElementById('myTable');
const cells = table.getElementsByTagName('td');
const mirrorElementButton = document.getElementById('mirrorElementButton')
const rotateElementButton = document.getElementById('rotateElementButton')


mirrorElementButton.addEventListener('click',mirrorElement)
rotateElementButton.addEventListener('click', rotateElement)
for (let cell of cells) {
    cell.addEventListener('click', handleGameTableClick);
    cell.addEventListener('mouseover',handleShadowRendering)
    cell.addEventListener('mouseout',handleShadowCleanup)
}
