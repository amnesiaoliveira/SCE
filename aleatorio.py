import mysql.connector
from datetime import datetime, timedelta
import random

# Conectar ao banco de dados
conn = mysql.connector.connect(
    host="127.0.0.1",  # ou "localhost"
    user="root",  # Substitua pelo seu nome de usuário
    password="",  # Substitua pela sua senha
    database="estoque"
)
cursor = conn.cursor()

# Gerar dados para 1000 produtos
for i in range(1000000):
    nome = f"Produto {i+1}"
    quantidade = random.randint(1, 1000000)
    codigo = f"COD-{i+1:04d}"
    lote = f"LOTE-{i+1:04d}"

    data_criacao = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    data_fabricacao = (datetime.now() - timedelta(days=random.randint(1, 365))).strftime('%Y-%m-%d')
    validade = (datetime.now() + timedelta(days=random.randint(30, 365))).strftime('%Y-%m-%d')
    data_lancamento = (datetime.now() - timedelta(days=random.randint(1, 100))).strftime('%Y-%m-%d')
    categoria = random.choice(["Eletrônicos", "Roupas", "Alimentos", "Bebidas", "Ferramentas"])

    # Inserir produto no banco de dados
    query = """
        INSERT INTO produtos (nome, quantidade, codigo, lote, data_criacao, data_fabricacao, validade, data_lancamento, categoria)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
    """
    values = (nome, quantidade, codigo, lote, data_criacao, data_fabricacao, validade, data_lancamento, categoria)
    
    cursor.execute(query, values)

# Confirmar transação e fechar conexão
conn.commit()
cursor.close()
conn.close()

print("Cadastro de 1000 produtos concluído com sucesso!")
