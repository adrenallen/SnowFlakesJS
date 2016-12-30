
	<style>
		#arena{
			width:100%;
			height:100%;
			background:transparent;
		}
		#playground{
			width:100%;
			height:100%;
			background:transparent;
		}
	</style>
	
	<script>
		
		var flakes = new Array();	//array of snowflakes
		
		var numFlakes = 200;	//Starting flake count
		var maxFlakes = 1000;

		var displayFlakes = -1;	//flag to display flake count
		
		var animLoop;	//The loop animation
		
		document.addEventListener('keydown', flakeControls);
		
		//various controls for snow flakes
		function flakeControls(e){
			if(e.keyCode == 33){
				displayFlakes *= -1;	//page up toggles flake count display
			}else if(e.keyCode == 36){
				if(animLoop == null){
					animLoop = requestAnimFrame(flakeFall);
				}else{
					var ctx = getPlay().getContext('2d');
					ctx.fillStyle = 'white';
					ctx.font = '30px bold calibri';
					ctx.fillText("Paused - Press HOME key to play", 10, 100);
					window.cancelAnimationFrame(animLoop);
					animLoop = null;
				}
			}else if(e.keyCode == 45){
				addSnow();	//insert add snow
			}
			
		}
			
		//add 100 snow flakes
		function addSnow(){
			if(flakes.length < maxFlakes){
				for(var i = 0; i < 3; i++){
					flakes.push(new topsnowflake());
				}
			}
		}
		
		
		setTimeout(initplay, 1000);	//pause for doc loading (cannot use onlaod due to desktop using it)
		
		//Covers all browsers
		window.requestAnimFrame = (function(){
		  return  window.requestAnimationFrame       ||
				  window.webkitRequestAnimationFrame ||
				  window.mozRequestAnimationFrame    ||
				  function( callback ){
					window.setTimeout(callback, 1000 / 60);
				  };
		})();	
		
		//get canvas shortcut
		function getPlay(){
			return document.getElementById("playground");
		}
		
		//initialize the playground
		function initplay(){
			var can = getPlay();
			can.width = 2500;
			can.height = 1500;
			
			for(var i = 0; i < numFlakes; i++){
				flakes.push(new snowflake());
			}
			
			animLoop = requestAnimFrame(flakeFall);
			
		}
		
		//The loop
		function flakeFall(time){
			animLoop = requestAnimFrame(flakeFall);
			flakeCalcs();
			drawPlay();
		}
		
		//Draw each flake to the canvas
		function drawPlay(){
			var can = getPlay();
			var ctx = can.getContext('2d');
			ctx.clearRect(0,0, can.width, can.height);
			
			ctx.fillStyle = 'white';
			ctx.strokeStyle = 'white';
			ctx.lineWidth = 1;
			for(var i = 0; i < flakes.length; i++){
				ctx.beginPath();
				ctx.fillStyle = "rgba(255, 255, 255, "+flakes[i].opacity+")";
				ctx.arc(flakes[i].x, flakes[i].y, flakes[i].size, 0, 2*Math.PI);
				ctx.fill();
			}
			if(displayFlakes > 0){
				ctx.save();
				ctx.font = '30px bold calibri';
				ctx.fillStyle = 'white'
				ctx.fillText("Flakes: " + flakes.length, 10, 50);
				ctx.restore();
			}
		}
		
		//Calculate a tick for each flakes
		function flakeCalcs(){
			var can = getPlay();
		
			var rand;	//If 0 remove, if 1 keep, if 2 add
			for(var i = 0; i < flakes.length; i++){
				rand = Math.floor(Math.random()*3);
				
				flakes[i].x += (Math.ceil(Math.random()*-2)*(Math.floor(Math.random()*2)));
				
				if(flakes[i].x >= can.width){
					flakes[i].x = 0;
				}else if(flakes[i].x <= 0){
					flakes[i].x = can.width;
				}
				
				flakes[i].y += flakes[i].speed;
				if(flakes[i].y > can.height){
					flakes[i].y = 0;
					if(flakes.length < 5 || rand == 2){
						flakes.push(new topsnowflake());
					}else if(rand == 0){
						//if there are less than 5 flakes then push an extra on and don't splice
						flakes.splice(i,1);
					}
				}
			}
		}
		
		//snow flake object
		function snowflake(){
			this.size = Math.floor(5+Math.random()*5);
			this.speed = Math.floor(2+Math.random()*3);
			this.opacity = .4 + (Math.ceil((Math.random()*6))/10);
			this.x = Math.floor(Math.random()*(parseInt(getPlay().width)));
			this.y = Math.floor(Math.random()*(parseInt(getPlay().height)));
		}
		
		//snow flake at top object
		function topsnowflake(){
			this.size = Math.floor(5+Math.random()*5);
			this.speed = Math.floor(2+Math.random()*3);
			this.opacity = .4 + (Math.ceil((Math.random()*6))/10);
			this.x = Math.floor(Math.random()*(parseInt(getPlay().width)));
			this.y = 0;
		}
		
	</script>
		<div id='arena'>
			<canvas id='playground'>
				
			</canvas>
		</div>
			
