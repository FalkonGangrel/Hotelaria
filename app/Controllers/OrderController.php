<?php

class OrderController {
    public function generateLink($id) {
        AuthMiddleware::check(['admin','master','cliente']); // permitir cliente gerar link para seu pedido, admins para qualquer
        $token = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $expires = (new DateTime('+7 days'))->format('Y-m-d H:i:sP');
        $stmt = db()->prepare("UPDATE orders SET purchase_link_token = :token, purchase_link_expires_at = :exp WHERE id = :id");
        $stmt->execute(['token'=>$token,'exp'=>$expires,'id'=>$id]);
        return ['link'=>"https://seusite.com/purchase/{$token}", 'expires'=>$expires];
    }

    public function showPurchaseLink($token) {
        $stmt = db()->prepare("SELECT * FROM orders WHERE purchase_link_token = :t AND (purchase_link_expires_at IS NULL OR purchase_link_expires_at > now())");
        $stmt->execute(['t'=>$token]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$order) { http_response_code(404); echo 'Link inválido ou expirado'; exit; }
        // mostrar view pública com itens -> permitir pagamento
    }

    public function completePurchase($token) {
        // Validar token, validar estoque, abrir transação, decrementar estoque com SELECT ... FOR UPDATE
        // Inserir pagamento/alterar status = 'paid'
    }
}
