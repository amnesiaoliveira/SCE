# SCE (Sistema de Controle de Estoque)

Aplicação frontend para pequenos comércios organizarem produtos, entradas, saídas e relatórios de estoque.

## Tecnologias

- HTML5 e CSS3
- Bootstrap 5.3 via CDN
- JavaScript sem framework
- `localStorage` para persistência local dos dados

## Como executar

Abra `index.html` no navegador. Para usar um servidor local, execute na raiz do projeto:

```bash
python3 -m http.server 8000
```

Depois acesse `http://localhost:8000`.

## Fluxos disponíveis

- Cadastro e exclusão de produtos, com código único e estoque mínimo.
- Registro de entradas com lote e de saídas por setor.
- Bloqueio de saída acima do estoque disponível.
- Dashboard com totais e alertas de estoque mínimo.
- Relatório filtrável por período e impressão.

Os arquivos `.php` antigos permanecem no repositório como referência da primeira versão baseada em PHP/MySQL. A aplicação atual usa apenas `index.html`, `styles.css` e `app.js`.
