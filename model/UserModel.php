<?php
require_once __DIR__ . '/../config/config.php';
class UserModel {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    public function create($data) {
        $checkSql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->execute([':email' => $data['email']]);
        if ($checkStmt->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Email exists'];
        }
        $sql = "INSERT INTO users (first_name, last_name, email, phone, password, role) 
                VALUES (:first_name, :last_name, :email, :phone, :password, :role)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'] ?? null,
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':role' => $data['role'] ?? 'candidate'
        ]);
        return [
            'success' => true,
            'user_id' => $this->db->lastInsertId(),
            'message' => 'User created'
        ];
    }
    public function getById($id) {
        $sql = "SELECT user_id, first_name, last_name, email, phone, role 
                FROM users WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        if ($user) {
            return ['success' => true, 'user' => $user];
        }
        return ['success' => false, 'message' => 'User not found'];
    }
    public function getAll($limit = 10, $offset = 0) {
        $sql = "SELECT user_id, first_name, last_name, email, phone, role 
                FROM users ORDER BY user_id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll();
        $countSql = "SELECT COUNT(*) as total FROM users";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute();
        $total = $countStmt->fetch()['total'];
        return [
            'success' => true,
            'users' => $users,
            'total' => (int)$total
        ];
    }
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        $allowed = ['first_name', 'last_name', 'email', 'phone', 'role'];
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                $fields[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }
        if (empty($fields)) {
            return ['success' => false, 'message' => 'No fields to update'];
        }
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return ['success' => true, 'message' => 'User updated'];
    }
    public function delete($id) {
        $sql = "DELETE FROM users WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        if ($stmt->rowCount() > 0) {
            return ['success' => true, 'message' => 'User deleted'];
        }
        return ['success' => false, 'message' => 'User not found'];
    }
    public function login($email, $password) {
        $sql = "SELECT user_id, first_name, last_name, email, phone, password, role 
                FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        if ($user) {
            if (password_verify($password, $user['password'])) {
                unset($user['password']);
                return ['success' => true, 'user' => $user, 'message' => 'Login ok'];
            }
            elseif ($user['password'] === $password) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $updateSql = "UPDATE users SET password = :password WHERE user_id = :user_id";
                $updateStmt = $this->db->prepare($updateSql);
                $updateStmt->execute([
                    ':password' => $hashedPassword,
                    ':user_id' => $user['user_id']
                ]);
                unset($user['password']);
                return ['success' => true, 'user' => $user, 'message' => 'Login ok'];
            }
        }
        return ['success' => false, 'message' => 'Invalid credentials'];
    }
}
?>