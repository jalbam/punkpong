<?php
    //Se configura el BBClone:
    define("_BBC_PAGE_NAME", "spanish online");
    define("_BBCLONE_DIR", "../bbclone/");
    define("COUNTER", _BBCLONE_DIR."mark_page.php");
    if (is_readable(COUNTER)) include_once(COUNTER);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>PunkPong &copy; (por Joan Alba Maldonado)</title>
        <!-- (c) PunkPong - Programa realizado por Joan Alba Maldonado (granvino@granvino.com). Prohibido publicar, reproducir o modificar sin citar expresamente al autor original. -->
        <script language="JavaScript1.2" type="text/javascript">
            <!--

            //(c) PunkPong - Programa realizado por Joan Alba Maldonado (granvino@granvino.com). Prohibido publicar, reproducir o modificar sin citar expresamente al autor original.

            //Variable que guardara el primer evento de teclado en ejecutarse por primera vez (pude ser onkeydown u onkeypress):
            var primer_evento;

            //Variable que guarda la dimension del area de juego:
            var area_juego_x = 700; //Pixels de la horizontal.
            var area_juego_y = 400; //Pixels de la vertical.
            
            //Variable que guarda el alto de las paletas:
            var paletas_height = 80;
            //Variable que guarda el ancho de las paletas:
            var paletas_width = 20;

            //Variable que guarda el alto de la pelota:
            var pelota_height = 10;
            //Variable que guarda el ancho de la pelota:
            var pelota_width = 10;

            //Variable para saber si se ha de sacar la pelota:
            var se_ha_de_sacar = true;

            //Variable que guarda el ultimo ganador (para saber quien saca):
            var ultimo_ganador = "usuario";

            //Varialbe que define si la pelota esta en movimiento o quita:
            var pelota_moviendose = false;

            //Variable que guardara el setInterval que llamara a la funcion de mover_pelota:
            var pelota_movimiento = setInterval("", 10000);
            
            //Variable que guardara el setInterval que hara moverse al ordenador:
            var ordenador_movimiento = setInterval("", 10000);

            //Variable que contiene el numero de pixels que la pelota se desplaza:
            var desplazamiento_x = 4; //Desplazamiento horizontal.
            var desplazamiento_y = 1; //Desplazamiento vertical.
            
            //Variable que contiene los milisegundos entre movimiento y movimiento, para la pelota:
            var velocidad_pelota_inicial = 25; //La velocidad al empezar.
            var velocidad_pelota = velocidad_pelota_inicial; //Aqui se ira decrementando (mas rapido) cuando se requiera.

            //Variable que contendra un numero aleatorio para saber sobre que extremo de la paleta ha de rematar el ordenador:
            var vertical_aleatorio = 0;

            //Variable que guarda la puntuacion del usuario:
            var puntuacion = 0;
            
            //Variables que guardan el numero de goles de cada jugador:
            var goles_ordenador = 0; //Goles del ordenador.
            var goles_usuario = 0; //Goles del usuario.

            //Variable que define los reflejos del ordenador (se incrementa segun nivel):
            var reflejos_iniciales = 0; //Reflejos iniciales.
            var reflejos_ordenador = reflejos_iniciales; //Reflejos que iran aumentando conforme el nivel.

            //Variable que guarda el numero de nivel:
            var nivel = 1; //Nivel inicial.
            
            //Numero de vidas inicial:
            var vidas_inicial = 5;
            var vidas = vidas_inicial;
            
            //Varialbe que impide el movimiento de la paleta del usuario (para cuando se inicia el juego):
            var impedir_movimiento = true;

            //Variable para saber con que se controlara el juego:
            var tipo_control = "teclado";


            //Funcion que inicia el juego:
            function iniciar_juego()
             {
                
                //Se borran los intervalos:
                clearInterval(pelota_movimiento); //Se para la pelota.
                clearInterval(ordenador_movimiento); //Se para la paleta del ordenador.
                
                //La pelota ya no se mueve:
                pelota_moviendose = false;
                
                //Se debe sacar:
                se_ha_de_sacar = true;
                
                //El ultimo ganador (el primero en tirar) es el usuario:
                ultimo_ganador = "usuario";
                
                //Setear velocidad a inicial, desplazamientos a inicial, vidas a tope, puntuacion a cero, etc...
                vidas = vidas_inicial;
                
                //Se setea la puntuacion a cero:
                puntuacion = 0;
                
                //Se setean los goles de ambos a cero:
                goles_ordenador = 0;
                goles_usuario = 0;
                
                //Se setea el nivel al primero (inicial):
                nivel = 1;
                
                //Se setea la velocidad de la pelota a la inicial:
                velocidad_pelota = velocidad_pelota_inicial;
                
                //Se setean los reflejos del ordenador a los iniciales:
                reflejos_ordenador = reflejos_iniciales;
                
                //La variable para saber sobre que lado golpear la paleta del ordenador a la pelota se setea a cero:
                vertical_aleatorio = 0;
                
                //Se setea el desplazamiento inicial de la pelota:
                desplazamiento_x = 2;
                
                desplazamiento_y = 1;
                
                //Se actualizan los marcadores:
                actualizar_marcadores();

                //Se posiciona la pelota en la paleta:
                posicionar_pelota(0);
                
                //Se hace visisble el cartel de anuncios e inicia la cuentra atras:
                setTimeout("document.getElementById('cartel_anuncios').style.visibility = 'visible'; document.getElementById('cartel_anuncios').innerHTML = 'Cuenta<br>atr&aacute;s<br>3';", 1000);
                setTimeout("document.getElementById('cartel_anuncios').innerHTML = 'Cuenta<br>atr&aacute;s<br>2';", 2000);
                setTimeout("document.getElementById('cartel_anuncios').innerHTML = 'Cuenta<br>atr&aacute;s<br>1';", 3000);
                
                //Despues de la cuenta atras, se anuncia que comienza el juego y se desbloquea la paleta del usuario para que se pueda mover:
                setTimeout("document.getElementById('cartel_anuncios').innerHTML = 'Comienza<br>el<br>juego'; impedir_movimiento = false;", 4000);
                setTimeout("document.getElementById('cartel_anuncios').style.visibility = 'hidden';", 7000); //Se quita el cartel a los 3 segundos del "Comienza el juego".
                
             }

            //Funcion que comienza el movimiento de la pelota:
            function sacar_pelota()
             {
                //Calcular si se ha de sacar:
                //if (se_ha_de_sacar) { sadfsd }
                if (!pelota_moviendose && se_ha_de_sacar)
                 {
                    //Se setea la variable para saber que ya se ha sacado:
                    se_ha_de_sacar = false;
                        
                    //Se setea la variable para saber que la pelota ha comenzado a moverse:
                    pelota_moviendose = true;

                    //Se destruye el movimiento anterior, si aun existiera:
                    clearInterval(pelota_movimiento);

                    //Si al que le toca sacar es el usuario:
                    if (ultimo_ganador == "usuario")
                     {
                       //Se define el desplazamiento (positivo):
                       desplazamiento_x = 4;
                       desplazamiento_y = 1;

                       //Numero aleatorio para calcular en un lado de la paleta o en otro:
                       vertical_aleatorio = parseInt(Math.random() * 40);
                     }
                    else if (ultimo_ganador == "ordenador")
                     {
                       //Se define el desplazamiento (positivo):
                       desplazamiento_x = -4;
                       desplazamiento_y = -1;
                     }

                   //Se comienza a mover la pelota 2x2 pixels, cada X (velocidad_pelota) milisegundos:
                   pelota_movimiento = setInterval("mover_pelota();", velocidad_pelota);

                 }
             }

            //Funcion que posiciona la pelota en la paleta de quien corresponde:
            function posicionar_pelota(posicion_y)
             {
                 //Si la pelota no esta moviendose y se debe sacar:
                 if (!pelota_moviendose && se_ha_de_sacar)
                  {

                    //Se posiciona la pelota verticalmente, segun el parametro enviado:
                    document.getElementById("pelota").style.top = posicion_y + 36 + "px";
                   
                    //Si el ultimo ganador es el usuario (el que saca), posicionamos la pelota en su paleta:
                    if (ultimo_ganador == "usuario") { document.getElementById("pelota").style.left = 30 + paletas_width + "px"; }
                    //...y si el ganador es el ordenador, se pone la pelota en su paleta para que saque el:
                    else if (ultimo_ganador == "ordenador") { document.getElementById("pelota").style.left = area_juego_x - 30 - pelota_height - paletas_width + "px"; }
                  }

                //Se actualizan los marcadores:
                actualizar_marcadores();

                //Si se marcan 3 goles (o mas, por si acaso), se pasa de nivel:
                if (goles_usuario >= 3) { pasar_nivel(); }
                
                //Si se pierden todas las vidas, se alerta del GameOver y se reinicia el juego:
                if (vidas < 0)
                 {
                    //Se setea conforme la pelota ya no se mueve:
                    pelota_moviendose = false;
                    //Se setea conforme ya no se ha de sacar:
                    se_ha_de_sacar = false;
                    //Se para la pelota:
                    clearInterval(pelota_movimiento);
                    //Se detiene la paleta del ordenador:
                    clearInterval(ordenador_movimiento);

                    //Se posiciona la paleta del usuario arriba del todo:
                    document.getElementById("paleta_usuario").style.top = "0px";

                    //Se anuncia que se ha acabado el juego:
                    document.getElementById("cartel_anuncios").innerHTML = "Fin<br>del<br>juego";
                    document.getElementById("cartel_anuncios").style.visibility = "visible";
                    setTimeout("document.getElementById('cartel_anuncios').style.visibility = 'hidden';", 3000);

                    //Se alerta del fin de juego:
                    alert("Game Over");

                    impedir_movimiento = true;
                 
                    //En 3 segundos (3000 milisegundos) comienza el nuevo juego:
                    setTimeout("iniciar_juego();", 3000);
                 }
             }

            //Funcion que captura las teclas pulsadas y realiza la accion correspondiente (llama a mover_paleta):
            function tecla_pulsada(e, evento_actual)
             {
                //Si el primer evento esta vacio, se le introduce como valor el evento actual (el que ha llamado a esta funcion):
                if (primer_evento == "") { primer_evento = evento_actual; }
                //Si el primer evento no es igual al evento actual (el que ha llamado a esta funcion), se vacia el primer evento (para que a la proxima llamada entre en la funcion) y se sale de la funcion:
                if (primer_evento != evento_actual) { primer_evento = ""; return; }

                //Si el control seleccionado no es el teclado, sale de la funcion:
                if (tipo_control != "teclado") { return; }

                //Capturamos la tacla pulsada, segun navegador:
                if (e.keyCode) { var unicode = e.keyCode; }
                //else if (event.keyCode) { var unicode = event.keyCode; }
                else if (window.Event && e.which) { var unicode = e.which; }
                else { var unicode = 40; } //Si no existe, por defecto se utiliza la flecha hacia abajo.

                //Si el movimiento de la paleta del usuario esta impedido, se sale de la funcion:
                if (impedir_movimiento) { return false; }

                //Definimos las variables de posicion (Y) del personaje:
                var posicion_y = parseInt(document.getElementById("paleta_usuario").style.top);

                //Si se pulsa la flecha hacia arriba, se restan 40 pixels verticales:
                if (unicode == 38) { posicion_y -= 40; }
                //Si se pulsa la flecha hacia abajo, se suman 40 pixels verticales:
                else if (unicode == 40) { posicion_y += 40; }

                //Codigos de teclas de disparo: 17 (ctrl), 16 (shift), 32 (space), 13 (return), 45 (insert), 96 (0), 190 (.).
                //Si el codigo es una de las teclas de disparo:
                else if (unicode == 39 || unicode == 17 || unicode == 16 || unicode == 32 || unicode == 13 || unicode == 45 || unicode == 96 || unicode == 190)
                 {
                    //Si la pelota no esta moviendose, se ha de sacar y el que ha de sacar es el usuario:
                    if (!pelota_moviendose && se_ha_de_sacar && ultimo_ganador == "usuario")
                     {
                        //Se saca la pelota:
                        sacar_pelota();
                     }
                 }

                //Se mueve la paleta, a las nuevas coordenadas (si existen):
                mover_paleta(posicion_y, "usuario");
  
                //Sale de la funcion, retornando true:
                return true;
             }
             
            
            //Funcion que mueve la paleta con el raton:
            function mover_raton(e)
             {
                //Si el control seleccionado no es el raton, sale de la funcion:
                if (tipo_control != "raton") { return; }
                
                //Si el movimiento de la paleta del usuario esta impedido, se sale de la funcion:
                if (impedir_movimiento) { return false; }
                
                //Variable para saber si estamos en Internet Explorer o no:
                var ie = document.all ? true : false;
                //Si estamos en internet explorer, se recogen las coordenadas del raton de una forma:
                if (ie)
                 {
                   posicion_y_raton = event.clientY + document.body.scrollTop;
                 }
                //...pero en otro navegador, se recogen de otra forma:
                else
                 {
                    document.captureEvents(Event.MOUSEMOVE);
                    posicion_y_raton = e.pageY;
                 } 
                //Si las coordenadas X o Y del raton son menores que cero, se ponen a cero:
                if (posicion_y_raton < 0) { posicion_y_raton = 0; }
                if (posicion_y_raton >= area_juego_y) { posicion_y_raton = area_juego_y - parseInt(paletas_height/2) + parseInt(pelota_height*2); }

                //Definimos las variables de posicion (Y) del personaje:
                var posicion_y = parseInt(document.getElementById("paleta_usuario").style.top);

                //Se setea la variable de la posicion (Y) de la paleta igual que la del raton:
                posicion_y = posicion_y_raton - parseInt(paletas_height/2) - (pelota_height*2);

                //Se mueve la paleta, a las nuevas coordenadas (si existen):
                mover_paleta(posicion_y, "usuario");
             }
             
             
            //Funcion que saca la pelota al hacer click con el raton:
            function hacer_click()
             {
                //Si el control seleccionado no es el raton, sale de la funcion:
                if (tipo_control != "raton") { return; }
                
                //Si el movimiento de la paleta del usuario esta impedido, se sale de la funcion:
                if (impedir_movimiento) { return false; }
                
                //Si la pelota no esta moviendose, se ha de sacar y el que ha de sacar es el usuario:
                if (!pelota_moviendose && se_ha_de_sacar && ultimo_ganador == "usuario")
                 {
                     //Se saca la pelota:
                     sacar_pelota();
                 }
             }
            
             
             
            //Funcion que mueve al enemigo:
            function mover_enemigo(pelota_y)
             {
                //Si pelota_moviendose = false, salir de la funcion:
                if (!pelota_moviendose) { return false; }
                
                //Variable que guarda la nueva posicion vertical del enemigo:
                var enemigo_y = parseInt(document.getElementById("paleta_ordenador").style.top);

                //Variable aleatoria para definir si el enemigo va a moverse o no:
                var moverse_enemigo = parseInt(Math.random() * 10); //Va del 0 al 9, y si es mayor que 8 entonces no se mueve.
                if (moverse_enemigo > reflejos_ordenador) { return false; } //Si es mayor a 8, sale de la funcion (el enemigo no se mueve).
                
                //Si la pelota esta mas arriba que la paleta del enemigo, el enemigo sube:
                if (pelota_y < enemigo_y)
                 {
                    if (enemigo_y - 10 >= 0 && enemigo_y - 10 <= pelota_y)
                     {
                        enemigo_y -= 10;
                     }
                    else if (enemigo_y - 4 >= 0 && enemigo_y - 4 <= pelota_y)
                     {
                        enemigo_y -= 4;
                     }
                    else if (enemigo_y - 2 >= 0 && enemigo_y - 2 <= pelota_y)
                     {
                        enemigo_y -= 2;
                     }
                    else if (enemigo_y - 1 >= 0 && enemigo_y - 1 <= pelota_y)
                     {
                        enemigo_y -= 1;
                     }
                    else if (enemigo_y - 10 >= 0)
                     {
                        enemigo_y -= 10;
                     }
                    else if (enemigo_y - 4 >= 0)
                     {
                        enemigo_y -= 4;
                     }
                    else if (enemigo_y - 2 >= 0)
                     {
                        enemigo_y -= 2;
                     }
                    else if (enemigo_y - 1 >= 0)
                     {
                        enemigo_y -= 1;
                     }

                    if (enemigo_y - vertical_aleatorio >= 0 && enemigo_y - vertical_aleatorio <= pelota_y)
                     {
                        enemigo_y -= vertical_aleatorio;
                     }

                 }
                //...pero si la pelota esta mas abajo que la paleta del enemigo, el enemigo baja:
                else if (pelota_y > enemigo_y + paletas_height)
                 {
                    if (enemigo_y + 10 <= area_juego_y && enemigo_y + 10 <= pelota_y)
                     {
                        enemigo_y += 10;
                     }
                    if (enemigo_y + 4 <= area_juego_y && enemigo_y + 4 <= pelota_y)
                     {
                        enemigo_y += 4;
                     }
                    else if (enemigo_y + 2 <= area_juego_y && enemigo_y + 2 <= pelota_y)
                     {
                        enemigo_y += 2;
                     }
                    else if (enemigo_y + 1 <= area_juego_y && enemigo_y + 1 <= pelota_y)
                     {
                        enemigo_y += 1;
                     }
                    else if (enemigo_y + 10 <= area_juego_y)
                     {
                        enemigo_y += 10;
                     }
                    else if (enemigo_y + 4 <= area_juego_y)
                     {
                        enemigo_y += 4;
                     }
                    else if (enemigo_y + 2 <= area_juego_y)
                     {
                        enemigo_y += 2;
                     }
                    else if (enemigo_y + 1 <= area_juego_y)
                     {
                        enemigo_y += 1;
                     }


                    if (enemigo_y + vertical_aleatorio <= area_juego_y && enemigo_y + vertical_aleatorio <= pelota_y)
                     {
                        enemigo_y += vertical_aleatorio;
                     }
                 }
                
                //Mueve al enemigo con la nueva configuracion:
                mover_paleta(enemigo_y, "ordenador");
             }
            
            //Funcion que mueve la paleta segun las ordenadas (vertical) enviadas por tecla_pulsada:
            function mover_paleta(posicion_y, quien_mueve)
             {
                //Se define la variable para saber si se ha movido la paleta o no:
                var se_ha_movido_paleta = false;
                
                //Si la posicion enviada esta fuera de la zona de juego, sale de la funcion sin mover la paleta y retornando false:
                if (posicion_y < 0 || posicion_y > area_juego_y - paletas_height) { return false; }
                //Si el que mueve es el usuario, mueve su paleta a la nueva posicion:
                else if (quien_mueve == "usuario") { document.getElementById("paleta_usuario").style.top = posicion_y + "px"; se_ha_movido_paleta = true; }
                //...y si el que mueve es el ordenador, mueve su paleta a la nueva posicion:
                else if (quien_mueve == "ordenador") { document.getElementById("paleta_ordenador").style.top = posicion_y + "px"; se_ha_movido_paleta = true; }

                //Si la pelota no se esta moviendo, y se ha de sacar y se ha movido la paleta, mover tambien la pelota:
                if (!pelota_moviendose && se_ha_de_sacar && se_ha_movido_paleta && quien_mueve == ultimo_ganador) { posicionar_pelota(posicion_y); }
              
                //Sale de la funcion, retornando true:
                return true;
             }

            //Funcion que mueve la pelota:
            function mover_pelota ()
             {
                //Si la pelota esta parada, salir de la funcion:
                if (!pelota_moviendose) { return; }
                
                //if (document.getElementById("pelota").style.top < 0 || document.getElementById("pelota").style.left > area_juego_y) { return; }
                
                //Mueve la pelota:
                document.getElementById("pelota").style.left = parseInt(document.getElementById("pelota").style.left) + desplazamiento_x + "px"; //Horizontalmente.
                //Si la nueva posicion vertical va a ser menor que cero, dejarla arriba del todo (sin pasar el borde):
                if (parseInt(document.getElementById("pelota").style.top) + desplazamiento_y < 0)
                 {
                    document.getElementById("pelota").style.top = "0px"; //Se pone arriba del todo, sin pasar el borde.
                 }
                //...y si la nueva posicion vertical va a ser mayor que el alto del area de juego, dejarla abajo del todo (sin pasar el borde):
                else if (parseInt(document.getElementById("pelota").style.top) + desplazamiento_y + pelota_width > area_juego_y)
                 {
                    document.getElementById("pelota").style.top = area_juego_y - pelota_width + "px"; //Se pone abajo del todo, sin pasar el borde.
                 }
                //...y si no, la nueva posicion vertical es correcta y se aplica tal cual:
                else { document.getElementById("pelota").style.top = parseInt(document.getElementById("pelota").style.top) + desplazamiento_y + "px"; } //Verticalmente. 
                
                //Calcular colision:
                calcular_colision(parseInt(document.getElementById("pelota").style.left), parseInt(document.getElementById("pelota").style.top));
                
                //Mover al enemigo:
                mover_enemigo(parseInt(document.getElementById("pelota").style.top));
                
             }
            
            //Funcion que calcula la colision entre paleta y pelota, y entre pelota y pared:
            function calcular_colision (pelota_x, pelota_y)
             {
                //Calcular si ha colisionado con alguna pared (de arriba o abajo):
                if (pelota_y <= 0 || pelota_y >= 400 - pelota_height)
                 {
                    //Cancela el movimiento de la pelota:
                    clearInterval(pelota_movimiento);
                    //Setear los nuevos valores de desplazamiento de la pelota:
                    desplazamiento_y *= -1; //Se invierte el movimiento vertical.
                    //Mueve la pelota con los nuevos valores:
                    pelota_movimiento = setInterval("mover_pelota();", velocidad_pelota);
                    //Sale de la funcion:
                    return;
                 }

                //Variables que recogen las coordenadas de las paletas:
                var paleta_usuario_top = parseInt(document.getElementById("paleta_usuario").style.top);
                var paleta_usuario_left = parseInt(document.getElementById("paleta_usuario").style.left);
                var paleta_ordenador_top = parseInt(document.getElementById("paleta_ordenador").style.top);
                var paleta_ordenador_left = parseInt(document.getElementById("paleta_ordenador").style.left);
                
                //Variable para calcular la colision con el lateral de la paleta con la pelota:
                var pelota_contra_lateral = false;
                
                //Variable para saber si invertir o no el movimiento de y:
                var invertir_y = false;
                
                //Calcular si la pelota ha colisionado con el lateral superior de la paleta del usuario:
                if (pelota_x >= paleta_usuario_left && pelota_x + pelota_width <= paleta_usuario_left + paletas_width && pelota_y + pelota_height >= paleta_usuario_top && pelota_y <= paleta_usuario_top)
                 {
                    pelota_contra_lateral = true; //Ha colisionado la pelota contra el lateral superior de la paleta del usuario.
                    //Si la pelota iba hacia abajo, invertir movimiento:
                    if (desplazamiento_y > 0) { invertir_y = true; }
                 }
                //Calcular si la pelota ha colisionado con el lateral inferior de la paleta del usuario:
                else if (pelota_x >= paleta_usuario_left && pelota_x + pelota_width <= paleta_usuario_left + paletas_width && pelota_y <= paleta_usuario_top + paletas_height && pelota_y + pelota_height >= paleta_usuario_top + paletas_height)
                 {
                    pelota_contra_lateral = true; //Ha colisionado la pelota contra el lateral inferior de la paleta del usuario.
                    //Si la pelota iba hacia arriba, invertir movimiento:
                    if (desplazamiento_y < 0) { invertir_y = true; }
                 }
                //Calcular si la pelota ha colisionado con el lateral superior de la paleta del ordenador:
                else if (pelota_x >= paleta_ordenador_left && pelota_x + pelota_width <= paleta_ordenador_left + paletas_width && pelota_y + pelota_height >= paleta_ordenador_top && pelota_y <= paleta_ordenador_top)
                 {
                    pelota_contra_lateral = true; //Ha colisionado la pelota contra el lateral superior de la paleta del ordenador.
                    //Si la pelota iba hacia abajo, invertir movimiento:
                    if (desplazamiento_y > 0) { invertir_y = true; }
                 }
                //Calcular si la pelota ha colisionado con el lateral inferior de la paleta del ordenador:
                else if (pelota_x >= paleta_ordenador_left && pelota_x + pelota_width <= paleta_ordenador_left + paletas_width && pelota_y <= paleta_ordenador_top + paletas_height && pelota_y + pelota_height >= paleta_ordenador_top + paletas_height)
                 {
                    pelota_contra_lateral = true; //Ha colisionado la pelota contra el lateral inferior de la paleta del ordenador.
                    //Si la pelota iba hacia arriba, invertir movimiento:
                    if (desplazamiento_y < 0) { invertir_y = true; }
                 }

                //Si la pelota ha colisionado con el lateral de alguna paleta:
                if (pelota_contra_lateral)
                 {
                    //Se vuelve a setear la variable como estaba, por si acaso:
                    pelota_contra_lateral = false;
                    //Cancela el movimiento de la pelota:
                    clearInterval(pelota_movimiento);

                    //Setear los nuevos valores de desplazamiento de la pelota:
                    if (desplazamiento_x > 0) { desplazamiento_x = 8; }
                    else { desplazamiento_x = -8; }
                    if (desplazamiento_y > 0) { desplazamiento_y = 8; } 
                    else { desplazamiento_y = -8; }

                    //Si esta seteada a true la variable de invertir las y, se aplica:
                    if (invertir_y) { desplazamiento_y *= -1; }

                    //Mueve la pelota con los nuevos valores:
                    pelota_movimiento = setInterval("mover_pelota();", velocidad_pelota);
                    //Sale de la funcion:
                    return;
                 }

                //Variable para calcular la colision frontal o con la esquina de alguna de las paletas con la pelota:
                var pelota_contra_frontal = false;

                //Variable para saber de quien es la paleta con la que la pelota ha colisionado:
                var jugador_rematador = "";

                //Calcular si la pelota ha colisionado con el frontal de la paleta del usuario:
                if (pelota_y + pelota_height >= paleta_usuario_top && pelota_y <= paleta_usuario_top + paletas_height && pelota_x <= paleta_usuario_left + paletas_width && pelota_x + pelota_width >= paleta_usuario_left)
                 {
                    pelota_contra_frontal = true; //Ha colisionado la pelota contra el frontal de la paleta del usuario.
                    jugador_rematador = "usuario";
                 }
                //Calcular si la pelota ha colisionado con el frontal de la paleta del ordenador:
                else if (pelota_y + pelota_height >= paleta_ordenador_top && pelota_y <= paleta_ordenador_top + paletas_height && pelota_x + pelota_width >= paleta_ordenador_left && pelota_x <= paleta_ordenador_left)
                 {
                    pelota_contra_frontal = true; //Ha colisionado la pelota contra el frontal de la paleta del ordenador.
                    jugador_rematador = "ordenador";
                 }
                
                //Si la pelota ha colisionado con el frontal de alguna paleta:
                if (pelota_contra_frontal)
                 {
                    //Se vuelve a setear la variable como estaba, por si acaso:
                    pelota_contra_frontal = false;
                    //Cancela el movimiento de la pelota:
                    clearInterval(pelota_movimiento);
                    
                    //Setear los nuevos valores de desplazamiento vertical de la pelota, segun en que extremo haya colisionado:
                    if (jugador_rematador == "usuario")
                     {
                         //Se dan 10 puntos al usuario:
                         puntuacion += 10;
                         
                         //Se actualizan los marcadores:
                         actualizar_marcadores();
                         
                         if (pelota_y >= paleta_usuario_top && pelota_y <= paleta_usuario_top + 20)
                          {
                              if (desplazamiento_y > 0) { desplazamiento_y = 6; }
                              else { desplazamiento_y = -6; }
                          }
                         else if (pelota_y > paleta_usuario_top + 20 && pelota_y <= paleta_usuario_top + 30)
                          {
                              if (desplazamiento_y > 0) { desplazamiento_y = 4; }
                              else { desplazamiento_y = -4; }
                          }
                         else if (pelota_y > paleta_usuario_top + 30 && pelota_y <= paleta_usuario_top + 50)
                          {
                              if (desplazamiento_y > 0) { desplazamiento_y = 2; }
                              else { desplazamiento_y = -2; }
                          }
                         else if (pelota_y > paleta_usuario_top + 50 && pelota_y <= paleta_usuario_top + 60)
                          {
                              if (desplazamiento_y > 0) { desplazamiento_y = 4; }
                              else { desplazamiento_y = -4; }
                          }
                         else if (pelota_y > paleta_usuario_top + 60 && pelota_y <= paleta_usuario_top + 80)
                          {
                              if (desplazamiento_y > 0) { desplazamiento_y = 4; }
                              else { desplazamiento_y = -4; }
                          }
                        else
                         {
                              if (desplazamiento_y > 0) { desplazamiento_y = 10; }
                              else { desplazamiento_y = -10; }
                         }
                        
                        //Numero aleatorio para calcular en un lado de la paleta o en otro:
                        vertical_aleatorio = parseInt(Math.random() * 40);

                     }
                    else if (jugador_rematador == "ordenador")
                     {
                         if (pelota_y >= paleta_ordenador_top && pelota_y <= paleta_ordenador_top + 20)
                          {
                              if (desplazamiento_y > 0) { desplazamiento_y = 6; }
                              else { desplazamiento_y = -6; }
                          }
                         else if (pelota_y > paleta_ordenador_top + 20 && pelota_y <= paleta_ordenador_top + 30)
                          {
                              if (desplazamiento_y > 0) { desplazamiento_y = 4; }
                              else { desplazamiento_y = -4; }
                          }
                         else if (pelota_y > paleta_ordenador_top + 30 && pelota_y <= paleta_ordenador_top + 50)
                          {
                              if (desplazamiento_y > 0) { desplazamiento_y = 2; }
                              else { desplazamiento_y = -2; }
                          }
                         else if (pelota_y > paleta_ordenador_top + 50 && pelota_y <= paleta_ordenador_top + 60)
                          {
                              if (desplazamiento_y > 0) { desplazamiento_y = 4; }
                              else { desplazamiento_y = -4; }
                          }
                         else if (pelota_y > paleta_ordenador_top + 60 && pelota_y <= paleta_ordenador_top + 80)
                          {
                              if (desplazamiento_y > 0) { desplazamiento_y = 6; }
                              else { desplazamiento_y = -6; }
                          }
                        else
                         {
                              if (desplazamiento_y > 0) { desplazamiento_y = 10; }
                              else { desplazamiento_y = -10; }
                         }
                     }

                    //Se incrementan dos pixels el movimiento horizontal de la pelota:
                    if (desplazamiento_x > 0 && desplazamiento_x < 12) { desplazamiento_x += 2; }
                    else if (desplazamiento_y < 0 && desplazamiento_x > -12) { desplazamiento_x -= 2; }
                    
                    //Se invierte el movimiento de la pelota:
                    desplazamiento_x *= -1; //Se invierte el movimiento horizontal.
                    //desplazamiento_y *= -1; //Se invierte el movimiento vertical.
                    
                    //Mueve la pelota con los nuevos valores:
                    pelota_movimiento = setInterval("mover_pelota();", velocidad_pelota);
                    //Sale de la funcion:
                    return;
                 }
                
                //Variable para saber si se ha marcado algun gol:
                var se_ha_marcado = false;
                
                //Si ha marcado un gol el ordenador al usuario:
                if (pelota_x < 30 - pelota_width)
                 {
                    ultimo_ganador = "ordenador"; //Se setea como ultimo ganador al ordenador.
                    clearInterval(pelota_movimiento); //Se detiene la pelota.
                    goles_ordenador++; //Se incrementan los goles marcados por el ordenador.
                    se_ha_de_sacar = true; //Se setea la variable para saber que se ha de sacar de nuevo.
                    vidas--; //El usuario pierde una vida.

                    //Se anuncia que el ordenador ha marcado un gol:
                    if (vidas >= 0 && pelota_moviendose)
                     {
                        document.getElementById("cartel_anuncios").innerHTML = "Ordenador<br>ha<br>marcado";
                        document.getElementById("cartel_anuncios").style.visibility = "visible";

                        //Se setea para saber que se ha marcado un gol:
                        se_ha_marcado = true;
                     }

                    pelota_moviendose = false; //Se indica que la pelota ya no se esta moviendo.
                    posicionar_pelota(paleta_ordenador_top); //Se posiciona la pelota en la paleta del ordenador.
                    setTimeout("sacar_pelota();", 2000); //El ordenador saca la pelota despues de 2 segundos (2000 milisegundos).
                 }
                //..y si ha marcado un gol el usuario al ordenador:
                else if (pelota_x > area_juego_x - 30)
                 {
                    ultimo_ganador = "usuario"; //Se setea como ultimo ganador al usuario.
                    clearInterval(pelota_movimiento); //Se detiene la pelota.
                    goles_usuario++; //Se incrementan los goles marcados por el usuario.
                    puntuacion += 100; //Se dan 100 puntos.
                    se_ha_de_sacar = true; //Se setea la variable para saber que se ha de sacar de nuevo.

                    //Se anuncia que el usuario ha marcado un gol, siempre que no se haya de pasar nivel:
                    if (goles_usuario < 3 && pelota_moviendose)
                     {
                        document.getElementById("cartel_anuncios").innerHTML = "T&uacute;<br>has<br>marcado";
                        document.getElementById("cartel_anuncios").style.visibility = "visible";

                        //Se setea para saber que se ha marcado un gol:
                        se_ha_marcado = true;
                     }

                    pelota_moviendose = false; //Se indica que la pelota ya no se esta moviendo.
                    posicionar_pelota(paleta_usuario_top); //Se posiciona la pelota en la paleta del usuario.
                 }

                if (se_ha_marcado && vidas >= 0)
                 {
                    //Se muestra el marcador:
                    if (goles_usuario > goles_ordenador) { setTimeout("document.getElementById('cartel_anuncios').innerHTML = goles_usuario + ' - ' + goles_ordenador + '<br>Ganas<br>t&uacute;';", 3000); }
                    else if (goles_usuario < goles_ordenador) { setTimeout("document.getElementById('cartel_anuncios').innerHTML = goles_usuario + ' - ' + goles_ordenador + '<br>Gana el<br>ordenador';", 3000); }
                    else if (goles_usuario == goles_ordenador) { setTimeout("document.getElementById('cartel_anuncios').innerHTML = goles_usuario + ' - ' + goles_ordenador + '<br>Hay<br>Empate';", 3000); }
                    setTimeout("document.getElementById('cartel_anuncios').style.visibility = 'hidden';", 6000);
                 }

                return true;

             }

            
            //Funcion que actualiza los marcadores:
            function actualizar_marcadores()
             {
                //Actualiza la barra de estado:
                if (vidas >= 0) { document.getElementById("estado").innerHTML = "&nbsp; Vidas: "+vidas+" | Nivel: "+nivel+" | Puntos: "+puntuacion; }
                else { document.getElementById("estado").innerHTML = "&nbsp; Game Over | Nivel: "+nivel+" | Puntos: "+puntuacion; }
                
                //Actualizar marcadores:
                document.getElementById("marcador_usuario").innerHTML = goles_usuario;
                document.getElementById("marcador_ordenador").innerHTML = goles_ordenador;
             }


            //Funcion que pasa de nivel:
            function pasar_nivel()
             {
               
                //Se setea el marcador de goles a cero en ambos equipos:
                goles_usuario = 0;
                goles_ordenador = 0;

                //Se incrementa un nivel:
                nivel++;
                
                //Se incrementan los reflejos del ordenador, siempre que no haya llegado a su tope (9):
                if (reflejos_ordenador < 9) { reflejos_ordenador++; }
                
                //Se dan 500 puntos al usuario:
                puntuacion += 500;
                
                //Se incrementa la velocidad, siempre que esta sea mayor a 10:
                if (velocidad_pelota > 1) { velocidad_pelota -= 1; } //Decrementamos para aumentar velocidad.
                 
                //Se actualizan los marcadores:
                actualizar_marcadores();

                //Se anuncia que se ha pasado de nivel:
                document.getElementById("cartel_anuncios").innerHTML = "Bienvenido<br>al<br>nivel "+nivel;
                document.getElementById("cartel_anuncios").style.visibility = "visible";
                setTimeout("document.getElementById('cartel_anuncios').style.visibility = 'hidden';", 3000);
             }

            //-->
        </script>
    </head>
    <body onLoad="javascript:document.getElementById('div_control').style.visibility='visible'; document.getElementById('control_teclado').checked = true; iniciar_juego();" onKeyDown="javascript:tecla_pulsada(event, 'onkeypress');" onKeyPress="javascript:tecla_pulsada(event, 'onkeydown');" onMouseMove="javascript:mover_raton(event);" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#aaaadd">
        <!-- Recuadro (borde blanco): -->
        <div style="background:#ffffff; color:#ffffff; border:0px; padding:0px; width:708px; height:408px; left:16px; top:16px; position:absolute; font-size:1px; z-index:1;" id="recuadro_juego">
        </div>
        <!-- Fin de Recuadro (borde blanco). -->
        <!-- Area del juego: -->
        <div style="background:#000000; color:#000000; border:0px; padding:0px; width:700px; height:400px; left:20px; top:20px; position:absolute; font-size:1px; cursor:crosshair; z-index:2;" id="area_juego" onClick="javascript:hacer_click();">
            <!-- Paleta del usuario: -->
            <div style="background:#ff0000; color:#ff0000; border:0px; padding:0px; width:20px; height:80px; left:30px; top:0px; position:absolute; font-size:1px; z-index:7;" id="paleta_usuario">
            </div>
            <!-- Fin de Paleta del usuario. -->
            <!-- Porteria del usuario: -->
            <div style="background:#ffffff; color:#ffffff; border:0px; padding:0px; width:2px; height:400px; left:25px; top:0px; position:absolute; font-size:1px; z-index:5;" id="porteria_usuario">
            </div>
            <!-- Fin de Porteria del usuario. -->
            <!-- Medio campo: -->
            <div style="background:#ffffff; color:#ffffff; border:0px; padding:0px; width:2px; height:390px; left:349px; top:5px; position:absolute; font-size:1px; z-index:3;" id="medio_campo_linea">
            </div>
            <div style="background:#ffffff; color:#ffffff; border:0px; padding:0px; width:4px; height:4px; left:348px; top:198px; position:absolute; font-size:1px; z-index:4;" id="medio_campo_centro">
            </div>
            <!-- Fin de Medio campo. -->
            <!-- Cartel de anuncio: -->
            <div style="visibility:hidden; background:#aabb00; color:#111111; border:0px; padding:0px; width:100px; height:60px; left:300px; top:170px; position:absolute; font-size:14px; font-family:verdana; font-weight:bold; text-align:center; line-height:19px; filter:alpha(opacity=80); opacity:0.8; -moz-opacity:0.8; z-index:15;" id="cartel_anuncios">
            </div>
            <!-- Fin de Cartel de anuncio. -->
            <!-- Pelota: -->
            <div style="background:#ffff00; color:#ff0000; border:0px; padding:0px; width:10px; height:10px; left:50px; top:36px; position:absolute; font-size:1px; z-index:9;" id="pelota">
            </div>
            <!-- Fin de Pelota. -->
            <!-- Paleta del ordenador: -->
            <div style="background:#ff0000; color:#ff0000; border:0px; padding:0px; width:20px; height:80px; left:650px; top:0px; position:absolute; font-size:1px; z-index:8;" id="paleta_ordenador">
            </div>
            <!-- Fin de Paleta del ordenador. -->
            <!-- Porteria del ordenador: -->
            <div style="background:#ffffff; color:#ff0000; border:0px; padding:0px; width:2px; height:400px; left:673px; top:0px; position:absolute; font-size:1px; z-index:6;" id="porteria_ordenador">
            </div>
            <!-- Fin de Porteria del ordenador. -->
            <!-- Marcadores: -->
            <div style="background:transparent; color:#00ff00; border:0px; padding:0px; width:24px; height:20px; left:0px; top:2px; position:absolute; font-size:11px; font-family:verdana; font-weight:bold; text-align:center; z-index:10;" id="marcador_usuario">
                0
            </div>
            <div style="background:transparent; color:#00ff00; border:0px; padding:0px; width:24px; height:20px; left:676px; top:2px; position:absolute; font-size:11px; font-family:verdana; font-weight:bold; text-align:center; z-index:11;" id="marcador_ordenador">
                0
            </div>
            <!-- Fin de Marcadores. -->
        </div>
        <!-- Fin de Area del juego. -->
        <!-- Barra de estado: -->
        <div style="background:#000033; color:#ffff00; border:0px; padding:0px; width:708px; height:20px; left:16px; top:426px; position:absolute; font-size:14px; font-family:verdana; font-weight:bold; line-height:19px; z-index:12;" id="estado">
            &nbsp; Cargando...
        </div>
        <!-- Fin de Barra de estado. -->
        <!-- Informacion del autor: -->
        <div style="visibility:visible; left:300px; top:428px; width:400px; height:15px; position:absolute; border:0px; padding:0px; background:transparent; color:#ffffff; text-align:right; line-height:15px; text-decoration:none; font-weight:bold; font-family:verdana; font-size:9px; z-index:13;" id="autor">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PunkPong&copy;  por Joan Alba Maldonado
        </div>
        <!-- Fin de Informacion del autor. -->
        <div id="div_control" style="left:20px; top:450px; height:0px; position:absolute; visibility:hidden; border:0px; padding:0px; background:transparent; color:#222222; text-align:left; line-height:20px; text-decoration:none; font-family:verdana; font-size:14px; z-index:14;">
            Control:
            <form style="display:inline;" name="formulario_control">
                <label for="control_teclado" onClick="javascript:tipo_control='teclado'; document.getElementById('div_desenfocar').style.visibility='visible'; formulario_control.desenfocar.focus(); document.getElementById('div_desenfocar').style.visibility='hidden';"><input type="radio" id="control_teclado" name="control" value="teclado" onClick="javascript:t�po_control='teclado'; document.getElementById('div_desenfocar').style.visibility='visible'; formulario_control.desenfocar.focus(); document.getElementById('div_desenfocar').style.visibility='hidden';" checked>Teclado</label> <label for="control_raton" onClick="javascript:tipo_control='raton'; document.getElementById('div_desenfocar').style.visibility='visible'; formulario_control.desenfocar.focus(); document.getElementById('div_desenfocar').style.visibility='hidden';"><input type="radio" id="control_raton" name="control" value="raton" onClick="javascript:tipo_control='raton'; document.getElementById('div_desenfocar').style.visibility='visible'; formulario_control.desenfocar.focus(); document.getElementById('div_desenfocar').style.visibility='hidden';">Rat&oacute;n</label>
                <div style="visibility:hidden; position:absolute;" id="div_desenfocar"><input type="text" name="desenfocar"></div>
            </form>
        </div>
        <!-- Informacion: -->
        <div style="left:20px; top:480px; height:0px; position:absolute; border:0px; padding:0px; background:transparent; color:#222222; text-align:left; line-height:20px; text-decoration:none; font-family:verdana; font-size:14px; z-index:14;">
            &copy; <b>PunkPong</b> 0.26a
            <br>
            &nbsp;&nbsp;por <i>Joan Alba Maldonado</i> (<a href="mailto:granvino@granvino.com;">granvino@granvino.com</a>) &nbsp;<sup>(100% DHTML)</sup>
            <br>&nbsp;&nbsp;- Prohibido publicar, reproducir o modificar sin citar expresamente al autor original.
            <br>
            &nbsp;&nbsp;<tt>* Utiliza las flechas del teclado para mover, y la flecha derecha
            <br>
            &nbsp;&nbsp; (tambien el espacio, el control, el shift o el intro) para sacar la pelota (cuando sea tu turno).
            <br>
            &nbsp;&nbsp; En Opera, dejar el puntero del rat&oacute;n encima del area de juego.
            <br>
            &nbsp;&nbsp; <b>Cada 3 goles se pasa de nivel y el enemigo es m&aacute;s dif&iacute;cil.</b></tt>
            <br>
            &nbsp;&nbsp;<i>Dedicado a Yasmina Llaveria del Castillo</i>
        <!-- Fin de Informacion. -->
    </body>
</html>
