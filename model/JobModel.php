<?php
require_once __DIR__ . '/../config/config.php';

class JobModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($data) {
        $sql = "INSERT INTO offers (id_companies, title, location, description, contract_type, published_date, salary) 
                VALUES (:company_id, :title, :location, :description, :employment_type, :published_date, :salary)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':company_id' => $data['company_id'],
            ':title' => $data['title'],
            ':location' => $data['location'] ?? null,
            ':description' => $data['description'] ?? null,
            ':employment_type' => $data['employment_type'] ?? 'CDI',
            ':published_date' => $data['published_date'] ?? date('Y-m-d'),
            ':salary' => $data['salary'] ?? null
        ]);
        
        return [
            'success' => true,
            'job_id' => $this->db->lastInsertId(),
            'message' => 'Job created'
        ];
    }
    
    public function getById($id) {
        $sql = "SELECT o.offers_id as job_id, o.title, o.description, o.location, o.contract_type as employment_type, o.salary, o.id_companies as company_id, c.name as company_name 
                FROM offers o 
                LEFT JOIN companies c ON o.id_companies = c.id_companies 
                WHERE o.offers_id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $offer = $stmt->fetch();
        
        if ($offer) {
            $offer['salary_display'] = $this->formatSalary($offer['salary']);
            return ['success' => true, 'job' => $offer];
        }
        
        return ['success' => false, 'message' => 'Job not found'];
    }
    
    public function getAll($filters = [], $limit = 20, $offset = 0) {
        $sql = "SELECT o.offers_id as job_id, o.title, o.description, o.location, o.contract_type as employment_type, o.salary, c.name as company_name 
                FROM offers o 
                LEFT JOIN companies c ON o.id_companies = c.id_companies 
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (o.title LIKE :search OR c.name LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['location'])) {
            $sql .= " AND o.location LIKE :location";
            $params[':location'] = '%' . $filters['location'] . '%';
        }
        
        if (!empty($filters['contract_type'])) {
            $sql .= " AND o.contract_type = :contract_type";
            $params[':contract_type'] = $filters['contract_type'];
        }
        
        $countSql = "SELECT COUNT(*) as total FROM offers o LEFT JOIN companies c ON o.id_companies = c.id_companies WHERE 1=1";
        if (!empty($filters['search'])) {
            $countSql .= " AND (o.title LIKE :search OR c.name LIKE :search)";
        }
        if (!empty($filters['location'])) {
            $countSql .= " AND o.location LIKE :location";
        }
        if (!empty($filters['contract_type'])) {
            $countSql .= " AND o.contract_type = :contract_type";
        }
        
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];
        
        $sql .= " ORDER BY o.published_date DESC LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        $offers = $stmt->fetchAll();
        
        foreach ($offers as &$offer) {
            $offer['salary_display'] = $this->formatSalary($offer['salary']);
        }
        
        return [
            'success' => true,
            'jobs' => $offers,
            'total' => (int)$total,
            'limit' => $limit,
            'offset' => $offset
        ];
    }
    
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        $allowed = ['company_id', 'title', 'location', 'description', 'employment_type', 'salary'];
        
        foreach ($data as $key => $value) {
            if ($key === 'company_id') {
                $fields[] = "id_companies = :company_id";
                $params[":company_id"] = $value;
            } elseif ($key === 'employment_type') {
                $fields[] = "contract_type = :employment_type";
                $params[":employment_type"] = $value;
            } elseif (in_array($key, ['title', 'location', 'description', 'salary'])) {
                $fields[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }
        
        if (empty($fields)) {
            return ['success' => false, 'message' => 'No fields to update'];
        }
        
        $sql = "UPDATE offers SET " . implode(', ', $fields) . " WHERE offers_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return ['success' => true, 'message' => 'Job updated'];
    }
    
    public function delete($id) {
        $sql = "DELETE FROM offers WHERE offers_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        if ($stmt->rowCount() > 0) {
            return ['success' => true, 'message' => 'Job deleted'];
        }
        
        return ['success' => false, 'message' => 'Job not found'];
    }
    
    private function formatSalary($salary) {
        if ($salary) {
            if ($salary >= 1000) {
                return number_format($salary / 1000, 1) . 'k €';
            }
            return number_format($salary, 0) . ' €';
        }
        return 'Négociable';
    }
    
    public function getStats() {
        $stats = [];
        
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM offers");
        $stats['total'] = $stmt->fetch()['total'];
        
        $stmt = $this->db->query("SELECT contract_type, COUNT(*) as count FROM offers GROUP BY contract_type");
        $stats['by_type'] = $stmt->fetchAll();
        
        return ['success' => true, 'data' => $stats];
    }
}
?>
