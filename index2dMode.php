<html> 
<head> 
    <title>World domination</title> 
    <link rel="stylesheet" type="text/css" href="style.css"> 
</head> 

<body> 

<script src="js/three.min.js"></script> 

<script>

    // --allow-file-access-from-files should be added in Properties-> Target when starting chrome locally
    // so local files can be loaded. This is because of security restrictions

    var renderer, camera, scene, pointLight, worldPlane;
    var mouseDown = false, mouseX = 0, mouseY = 0;
    var minZoom = 120, maxZoom = 233;

    init();
    mainLoop();

    function init()
    {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////

        //renderer
        renderer = new THREE.WebGLRenderer();
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.body.appendChild(renderer.domElement);

        /////////////////////////////////////////////////////////////////////////////////////////////////////////

        //scene
        scene = new THREE.Scene();

        /////////////////////////////////////////////////////////////////////////////////////////////////////////

        //camera
        //vertical field of view, aspect ratio, near plane, far plane
        camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 1, 1000);
        camera.position.set(80, 100, 200);

        scene.add(camera);
 
        /////////////////////////////////////////////////////////////////////////////////////////////////////////

        var worldTexture = THREE.ImageUtils.loadTexture('textures/NASA_world_reduced1.jpg');
        var defaultWorldMaterial = new THREE.MeshPhongMaterial({ map: worldTexture });
        var geometry = new THREE.PlaneGeometry(1000, 500, 1, 1);
        worldPlane = new THREE.Mesh(geometry, defaultWorldMaterial);

        scene.add(worldPlane);

        /////////////////////////////////////////////////////////////////////////////////////////////////////////     

        // ambient light
        scene.add(new THREE.AmbientLight(0xffffff));

        var light = new THREE.DirectionalLight(0xffffff, 0.2);
        light.position.set(0, 50, 0);
        scene.add(light);

        /////////////////////////////////////////////////////////////////////////////////////////////////////////

        //listeners for mouse move, mouse down, mouse up, mouse wheel

        document.body.addEventListener('mousemove', function (e) { onMouseMove(e); }, false);
        document.body.addEventListener('mousedown', function (e) { onMouseDown(e); }, false);
        document.body.addEventListener('mouseup', function (e) { onMouseUp(e); }, false);
        document.body.addEventListener('mousewheel', function (e) { onMouseWheel(e); }, false);
        document.body.addEventListener('DOMMouseScroll', function (e) { onMouseWheel(e); }, false);//this is for Mozilla

    }

    function onMouseWheel(event)
    {
        var delta = 0;

        if (event.wheelDelta) //Chrome
        {
            delta = -event.wheelDelta / 20;
        }
        else if (event.detail) //Mozilla
        {
            delta = event.detail*3;
        }

        if (camera.position.z + delta > minZoom && camera.position.z + delta < maxZoom)
        {
            camera.translateZ(delta);
        }
    }

    function onMouseMove(event)
    {
        if (!mouseDown) {
            return;
        }

        var deltaX = event.clientX - mouseX;
        var deltaY = event.clientY - mouseY;
        mouseX = event.clientX;
        mouseY = event.clientY;

        moveCamera(deltaX, deltaY);
    }

    function moveCamera(deltaX, deltaY)
    {
        deltaX = -(deltaX / 5);
        deltaY = (deltaY / 5);

        worldPlane.geometry.computeBoundingBox();
        boundingBox = worldPlane.geometry.boundingBox;

        var offsetX = 200;
        var offsetY = 100;

        //we want camera movement to be limited by the dimensions
        //of the world plane. We dont want to go outside of the plane
        //we achieve this by stopping the camera movement when we reach certain offsets of
        //the dimensions of the plane

        if (camera.position.x + deltaX + offsetX > boundingBox.max.x ||
            camera.position.x + deltaX - offsetX < boundingBox.min.x)
        {
            deltaX = 0;
        }

        if (camera.position.y + deltaY + offsetY > boundingBox.max.y ||
            camera.position.y + deltaY - offsetY < boundingBox.min.y)
        {
            deltaY = 0;
        }

        camera.translateX(deltaX);
        camera.translateY(deltaY);
    }

    function onMouseDown(event)
    {
        mouseDown = true;
        mouseX = event.clientX;
        mouseY = event.clientY;
    }

    function onMouseUp(event)
    {
        mouseDown = false;
    }

    function mainLoop()
    {
        requestAnimationFrame(mainLoop);

        update();
        render();
    }

    function update()
    {
        
    }

    function render()
    {
        renderer.render(scene, camera);
    }

    function setVisibility(id, visibility)
    {
        document.getElementById(id).style.visibility = visibility;
    }

</script>

<!--Login button-->
<div style="position:absolute;top:0;right:0;margin-top:50px;margin-right:100px;">
    <button name ="login" id ="login" style="font-size:15pt;font-family:'Times New Roman'" value ="Login" onclick="setVisibility('openLogin', 'visible');"> Login </button>
</div>

<!--Login dialog-->
<div id="openLogin" class="loginDialog">
    <div>
        <a href="#openLogin" onclick="setVisibility('openLogin', 'hidden');" class="closeButtonLogin">X</a>

        <div style="position: relative; left: 10%;">
            <form id="Form1">
                <br /><br />

                <label style="font-size: 25pt; font-family: 'Times New Roman'">Login </label> <br /><br />

                Username <br />
                <input name="username" id="username" value="" style="font-size: 15pt; font-family: 'Times New Roman'" /><br /> <br />

                Password<br />
                <input name="password" id="password" value="" style="font-size: 15pt; font-family: 'Times New Roman'" /><br /><br />

                <input type="button" style="font-size: 15pt; font-family: 'Times New Roman'" value="Login" />
            </form>
        </div>
    </div>
</div>

<!--Register button-->
<div style="position:absolute;top:0;right:0;margin-top:50px;margin-right:200px;">
    <button name ="register" id ="Button2" style="font-size:15pt;font-family:'Times New Roman'" onclick="setVisibility('openRegister', 'visible');"> Register </button>
</div>

<!--Register dialog-->
<div id="openRegister" class="registerDialog">
    <div>
        <a href="#openRegister" onclick="setVisibility('openRegister', 'hidden');" title="Close" class="closeButtonRegister">X</a>

        <div style="position: relative; left: 13%;">
            <form id="Form2">
                <br /><br />

                <label style="font-size: 25pt; font-family: 'Times New Roman'">Register</label><br /><br />

                Email <br />
                <input name="email" id="Text3" value="" style="font-size: 15pt; font-family: 'Times New Roman'" /><br /><br />

                Username<br />
                <input name="username" id="Text1" value="" style="font-size: 15pt; font-family: 'Times New Roman'" /> <br /><br />

                Password
                <br />
                <input name="password" id="Text2" value="" style="font-size: 15pt; font-family: 'Times New Roman'" /><br /><br />

                Repeat password<br />
                <input name="repeatPassword" id="Text4" value="" style="font-size: 15pt; font-family: 'Times New Roman'" /><br /><br />

                <input type="button" style="font-size: 15pt; font-family: 'Times New Roman'" value="Register" />
            </form>
        </div>
    </div>
</div>

 <!--3d mode button-->
<div style="position:absolute;bottom:0;left:0;margin-bottom:10px;margin-left:50px;">
    <form action="index.php">
        <input type="submit" id ="Submit2" style="font-size:15pt;font-family:'Times New Roman'" value ="Go to 3D mode">
    </form>
</div>

 <!--play button-->
<div style="position:absolute;bottom:0;left:0;margin-bottom:50px;margin-left:50px;">
    <form action="TileMap.php">
        <input type="submit" id ="Submit2" style="font-size:15pt;font-family:'Times New Roman'" value ="Play">
    </form>
</div>

</body> 

</html>