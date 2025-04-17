# Backlog

## Épicos

### Gerenciamento de Produtos

### Controle de Entradas e Saídas

### Gerenciamento de Setores

### Relatórios e Alertas

### Autenticação e Segurança

### Integrações e APIs

## Histórias de Usuário

### Épico 1: Gerenciamento de Produtos

- **Como administrador**, quero cadastrar produtos com código, nome, categoria, unidade de medida e estoque mínimo, para organizar o inventário.
- **Como administrador**, quero editar/excluir produtos, para corrigir erros ou remover itens obsoletos.
- **Como usuário**, quero buscar produtos por código ou nome com autocomplete, para agilizar o registro de movimentações.

### Épico 2: Controle de Entradas e Saídas

- **Como usuário**, quero registrar entradas de produtos com código, quantidade, lote, data de fabricação e validade, para atualizar o estoque.
- **Como usuário**, quero registrar saídas de produtos com código, quantidade e setor de destino, para controlar a distribuição.
- **Como usuário**, quero ser notificado se a quantidade solicitada para saída exceder o estoque disponível, para evitar erros.

### Épico 3: Gerenciamento de Setores

- **Como administrador**, quero adicionar novos setores de destino, para suportar novas áreas do hospital.
- **Como administrador**, quero remover setores obsoletos, para manter a lista atualizada.

### Épico 4: Relatórios e Alertas

- **Como gerente**, quero visualizar um relatório de estoque atual com filtros por categoria ou armazém, para planejar compras.
- **Como gerente**, quero receber alertas sobre produtos com estoque abaixo do mínimo ou próximos ao vencimento, para evitar rupturas ou perdas.
- **Como administrador**, quero consultar o histórico de movimentações por produto ou lote, para auditorias.

### Épico 5: Autenticação e Segurança

- **Como usuário**, quero fazer login com usuário e senha, para acessar o sistema de forma segura.
- **Como administrador**, quero gerenciar permissões de usuários, para controlar quem pode registrar entradas, saídas ou editar produtos.
- **Como sistema**, devo proteger contra SQL Injection e XSS, para garantir a integridade dos dados.

### Épico 6: Integrações e APIs

- **Como desenvolvedor**, quero uma API REST para consultar e registrar movimentações, para integrar com outros sistemas hospitalares.
- **Como administrador**, quero exportar relatórios em CSV ou PDF, para análises externas.
