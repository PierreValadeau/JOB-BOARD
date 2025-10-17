<?php
require_once '../model/UserModel.php';
class UserController {
    private $userModel;
    public function __construct() {
        $this->userModel = new UserModel();
    }
    public function handleRequest() {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];
        $input = json_decode(file_get_contents('php://input'), true);
        switch ($method) {
            case 'GET':
                if (isset($_GET['id'])) {
                    $this->getUser($_GET['id']);
                } else {
                    $this->getAllUsers();
                }
                break;
            case 'POST':
                if (isset($input['action']) && $input['action'] === 'login') {
                    $this->loginUser($input);
                } else {
                    $this->createUser($input);
                }
                break;
            case 'PUT':
                if (isset($_GET['id'])) {
                    $this->updateUser($_GET['id'], $input);
                } else {
                    echo json_encode(['success' => false, 'message' => 'User ID required']);
                }
                break;
            case 'DELETE':
                if (isset($_GET['id'])) {
                    $this->deleteUser($_GET['id']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'User ID required']);
                }
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
    }
    private function getAllUsers() {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $result = $this->userModel->getAll($limit, $offset);
        echo json_encode($result);
    }
    private function getUser($id) {
        $result = $this->userModel->getById($id);
        echo json_encode($result);
    }
    private function createUser($data) {
        if (!isset($data['first_name']) || !isset($data['last_name']) || 
            !isset($data['email']) || !isset($data['password'])) {
            echo json_encode([
                'success' => false, 
                'message' => 'Missing required fields: first_name, last_name, email, password'
            ]);
            return;
        }
        $result = $this->userModel->create($data);
        echo json_encode($result);
    }
    private function updateUser($id, $data) {
        $result = $this->userModel->update($id, $data);
        echo json_encode($result);
    }
    private function deleteUser($id) {
        $result = $this->userModel->delete($id);
        echo json_encode($result);
    }
    private function loginUser($data) {
        if (!isset($data['email']) || !isset($data['password'])) {
            echo json_encode([
                'success' => false, 
                'message' => 'Email and password required'
            ]);
            return;
        }
        $result = $this->userModel->login($data['email'], $data['password']);
        echo json_encode($result);
    }
}
?>