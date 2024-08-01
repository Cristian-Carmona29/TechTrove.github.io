<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Clientes extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
    }
    public function index()
    {
        if (empty($_SESSION['correoCliente'])) {
            header('Location: ' . BASE_URL);
        }
        $data['perfil'] = 'si';
        $data['title'] = 'Tu Perfil';
        $data['categorias'] = $this->model->getCategorias();
        $data['verificar'] = $this->model->getVerificar($_SESSION['correoCliente']);
        $this->views->getView('principal', "perfil", $data);
    }
    public function registroDirecto()
    {
        if (isset($_POST['nombre']) && isset($_POST['clave'])) {
            if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['clave'])) {
                $mensaje = array('msg' => 'TODO LOS CAMPOS SON REQUERIDOS', 'icono' => 'warning');
            } else {
                $nombre = $_POST['nombre'];
                $correo = $_POST['correo'];
                $clave = $_POST['clave'];
                $verificar = $this->model->getVerificar($correo);
                if (empty($verificar)) {
                    $token = md5($correo);
                    $hash = $clave; // Guardar la contraseña en texto plano
                    $data = $this->model->registroDirecto($nombre, $correo, $hash, $token);
                    if ($data > 0) {
                        $_SESSION['idCliente'] = $data;
                        $_SESSION['correoCliente'] = $correo;
                        $_SESSION['nombreCliente'] = $nombre;
                        $mensaje = array('msg' => 'registrado con éxito', 'icono' => 'success', 'token' => $token);
                    } else {
                        $mensaje = array('msg' => 'error al registrarse', 'icono' => 'error');
                    }
                } else {
                    $mensaje = array('msg' => 'YA TIENES UNA CUENTA', 'icono' => 'warning');
                }
            }
            echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    public function enviarCorreo()
    {
        if (isset($_POST['correo']) && isset($_POST['token'])) {
            $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
            $token = htmlspecialchars($_POST['token']);

            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $mensaje = array('msg' => 'CORREO NO VÁLIDO', 'icono' => 'error');
                echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
                die();
            }

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = HOST_SMTP;
                $mail->SMTPAuth   = true;
                $mail->Username   = USER_SMTP;
                $mail->Password   = PASS_SMTP;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = PUERTO_SMTP;

                $mail->setFrom('felipe.291105@gmail.com', TITLE);
                $mail->addAddress($correo);

                $mail->isHTML(true);
                $mail->Subject = 'Mensaje desde la: ' . TITLE;
                $mail->Body    = 'Para verificar tu correo en nuestra tienda <a href="' . BASE_URL . 'clientes/verificarCorreo/' . $token . '">CLIC AQUÍ</a>';
                $mail->AltBody = 'GRACIAS POR LA PREFERENCIA';

                $mail->send();
                $mensaje = array('msg' => 'CORREO ENVIADO, REVISA TU BANDEJA DE ENTRADA - SPAM', 'icono' => 'success');
            } catch (Exception $e) {
                $mensaje = array('msg' => 'ERROR AL ENVIAR CORREO: ' . $mail->ErrorInfo, 'icono' => 'error');
            }
        } else {
            $mensaje = array('msg' => 'ERROR FATAL: Parámetros incompletos', 'icono' => 'error');
        }
        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function verificarCorreo($token)
    {
        $token = htmlspecialchars($token);
        $verificar = $this->model->getToken($token);
        if (!empty($verificar)) {
            $this->model->actualizarVerify($verificar['id']);
            header('Location: ' . BASE_URL . 'clientes');
        } else {
            echo "Token no válido o expirado.";
        }
    }

    //login directo
    public function loginDirecto()
    {
        if (isset($_POST['correoLogin']) && isset($_POST['claveLogin'])) {
            $correo = filter_var($_POST['correoLogin'], FILTER_SANITIZE_EMAIL);
            $clave = htmlspecialchars($_POST['claveLogin']);

            if (empty($correo) || empty($clave)) {
                $mensaje = array('msg' => 'TODO LOS CAMPOS SON REQUERIDOS', 'icono' => 'warning');
            } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $mensaje = array('msg' => 'CORREO NO VÁLIDO', 'icono' => 'error');
            } else {
                $verificar = $this->model->getVerificar($correo);
                if (!empty($verificar)) {
                    //if (password_verify($clave, $verificar['clave'])) {
                    if ($clave == $verificar['clave']) { // Comparar la contraseña en texto plano
                        $_SESSION['idCliente'] = $verificar['id'];
                        $_SESSION['correoCliente'] = $verificar['correo'];
                        $_SESSION['nombreCliente'] = $verificar['nombre'];
                        $mensaje = array('msg' => 'OK', 'icono' => 'success');
                    } else {
                        $mensaje = array('msg' => 'CONTRASEÑA INCORRECTA', 'icono' => 'error');
                    }
                } else {
                    $mensaje = array('msg' => 'EL CORREO NO EXISTE', 'icono' => 'warning');
                }
            }
            echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    //Recuperación contraseña
    public function solicitarRecuperacion()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['correoRecuperacion'])) {
            $correo = filter_var($input['correoRecuperacion'], FILTER_SANITIZE_EMAIL);

            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $mensaje = array('msg' => 'CORREO NO VÁLIDO', 'icono' => 'error');
                echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
                die();
            }

            $verificar = $this->model->getVerificar($correo);
            if (!empty($verificar)) {
                // Enviar correo de recuperación
                $this->enviarCorreoRecuperacion($correo, $verificar['clave']);
                $mensaje = array('msg' => 'CORREO DE RECUPERACIÓN ENVIADO', 'icono' => 'success');
            } else {
                $mensaje = array('msg' => 'EL CORREO NO EXISTE', 'icono' => 'warning');
            }
            echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
            die();
        } else {
            $mensaje = array('msg' => 'PARÁMETROS INCOMPLETOS', 'icono' => 'error');
            echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    //Enviar correo
    public function enviarCorreoRecuperacion($correo, $clave)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = HOST_SMTP;
            $mail->SMTPAuth   = true;
            $mail->Username   = USER_SMTP;
            $mail->Password   = PASS_SMTP;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = PUERTO_SMTP;

            $mail->setFrom('felipe.291105@gmail.com', TITLE);
            $mail->addAddress($correo);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de Contraseña - ' . TITLE;
            $mail->Body    = 'Tu contraseña es: ' . $clave;
            $mail->AltBody = 'Tu contraseña es: ' . $clave;

            $mail->send();
        } catch (Exception $e) {
            error_log('ERROR AL ENVIAR CORREO DE RECUPERACIÓN: ' . $mail->ErrorInfo);
        }
    }

    //registrar pedidos
    public function registrarPedido()
    {
        $datos = file_get_contents('php://input');
        $json = json_decode($datos, true);
        $pedidos = $json['pedidos'];
        $productos = $json['productos'];
        if (is_array($pedidos) && is_array($productos)) {
            $id_transaccion = $pedidos['id'];
            $monto = $pedidos['purchase_units'][0]['amount']['value'];
            $estado = $pedidos['status'];
            $fecha = date('Y-m-d H:i:s');
            $email = $pedidos['payer']['email_address'];
            $nombre = $pedidos['payer']['name']['given_name'];
            $apellido = $pedidos['payer']['name']['surname'];
            $direccion = $pedidos['purchase_units'][0]['shipping']['address']['address_line_1'];
            $ciudad = $pedidos['purchase_units'][0]['shipping']['address']['admin_area_2'];
            $id_cliente = $_SESSION['idCliente'];
            $data = $this->model->registrarPedido(
                $id_transaccion,
                $monto,
                $estado,
                $fecha,
                $email,
                $nombre,
                $apellido,
                $direccion,
                $ciudad,
                $id_cliente
            );
            if ($data > 0) {
                foreach ($productos as $producto) {
                    $temp = $this->model->getProducto($producto['idProducto']);
                    $this->model->registrarDetalle($temp['nombre'], $temp['precio'], $producto['cantidad'], $data, $producto['idProducto']);
                }
                $mensaje = array('msg' => 'pedido registrado', 'icono' => 'success');
            } else {
                $mensaje = array('msg' => 'error al registrar el pedido', 'icono' => 'error');
            }
        } else {
            $mensaje = array('msg' => 'error fatal con los datos', 'icono' => 'error');
        }
        echo json_encode($mensaje);
        die();
    }

    //listar productos pendientes
    public function listarPendientes()
    {
        $id_cliente = $_SESSION['idCliente'];
        $data = $this->model->getPedidos($id_cliente);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['accion'] = '<div class="text-center"><button class="btn btn-primary" type="button" onclick="verPedido(' . $data[$i]['id'] . ')"><i class="fas fa-eye"></i></button></div>';
        }
        echo json_encode($data);
        die();
    }
    public function verPedido($idPedido)
    {
        $data['pedido'] = $this->model->getPedido($idPedido);
        $data['productos'] = $this->model->verPedidos($idPedido);
        $data['moneda'] = MONEDA;
        echo json_encode($data);
        die();
    }

    public function salir()
    {
        session_destroy();
        header('Location: ' . BASE_URL);
    }
}
