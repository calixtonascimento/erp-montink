
# 🚀 ERP Montink

Sistema de gerenciamento de produtos, estoque, pedidos e cupons desenvolvido em **CodeIgniter 3** com ambiente Docker.

---

## 🐳 **Subindo o projeto com Docker**

### 1. Clone o repositório

```bash
git clone https://github.com/calixtonascimento/erp-montink.git
cd erp-montink
```

### 2. Configure as permissões (se necessário)

```bash
sudo chown -R $USER:$USER .
```

### 3. Suba os containers

```bash
docker-compose up -d
```

👉 O projeto ficará disponível em: [http://localhost:8788](http://localhost:8788)

---

## 🗄️ **Banco de Dados**

- O banco será criado automaticamente com o container.
- As tabelas são geradas a partir do arquivo `init.sql` que roda na primeira vez que o container do banco sobe.

### 🔗 Conexão com o banco:

| Parametro | Valor                          |
|------------|-------------------------------|
| Host       | db                            |
| Porta      | 3306 (interno) / 3307 (externo)|
| Database   | erp_montink                   |
| Usuário    | erp_montink_admin             |
| Senha      | teste123                      |

### Conectar via linha de comando (opcional):

```bash
docker exec -it montink_db mysql -u erp_montink_admin -p
```

---

## ⚙️ **Configurações importantes**

### 🔗 Base URL

No arquivo `application/config/config.php`:

```php
$config['base_url'] = 'http://localhost:8788/';
```

---

## 📂 **Estrutura dos containers**

| Serviço | Descrição             | Porta        |
|---------|------------------------|--------------|
| app     | Aplicação PHP + Apache | 8788         |
| db      | MariaDB 10.5           | 3307 externo |

---

## 🛑 **Parar os containers**

```bash
docker-compose down
```

## ♻️ **Resetar banco (opcional)**

```bash
docker-compose down -v
```

---

## ✍️ **Autor**

- [Calixto Nascimento](https://github.com/calixtonascimento)
