<?php
require_once __DIR__ . '/../config/config.php';
class CompanyModel {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    public function create($data) {
        $sql = "INSERT INTO companies (name, email, phone, location, description, website) 
                VALUES (:name, :email, :phone, :location, :description, :website)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'] ?? $data['name'] . '@company.com',
            ':phone' => $data['phone'] ?? null,
            ':location' => $data['location'] ?? null,
            ':description' => $data['description'] ?? null,
            ':website' => $data['website'] ?? null
        ]);
        return [
            'success' => true,
            'company_id' => $this->db->lastInsertId(),
            'message' => 'Company created'
        ];
    }
    public function getById($id) {
        $sql = "SELECT id_companies as company_id, name, email, phone, location, description, website FROM companies WHERE id_companies = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $company = $stmt->fetch();
        if ($company) {
            return ['success' => true, 'company' => $company];
        }
        return ['success' => false, 'message' => 'Company not found'];
    }
    public function getAll($limit = 10, $offset = 0) {
        $sql = "SELECT id_companies as company_id, name, email, phone, location, description, website FROM companies ORDER BY name LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $companies = $stmt->fetchAll();
        $countSql = "SELECT COUNT(*) as total FROM companies";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute();
        $total = $countStmt->fetch()['total'];
        return [
            'success' => true,
            'companies' => $companies,
            'total' => (int)$total
        ];
    }
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        $allowed = ['name', 'email', 'phone', 'location', 'description', 'website'];
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                $fields[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }
        if (empty($fields)) {
            return ['success' => false, 'message' => 'No fields to update'];
        }
        $sql = "UPDATE companies SET " . implode(', ', $fields) . " WHERE id_companies = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return ['success' => true, 'message' => 'Company updated'];
    }
    public function delete($id) {
        $sql = "DELETE FROM companies WHERE id_companies = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        if ($stmt->rowCount() > 0) {
            return ['success' => true, 'message' => 'Company deleted'];
        }
        return ['success' => false, 'message' => 'Company not found'];
    }
}
?>