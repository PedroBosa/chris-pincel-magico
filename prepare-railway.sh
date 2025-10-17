#!/bin/bash

# 🚂 Script de Preparação para Railway

echo "🚂 Chris Pincel Mágico - Preparação para Railway"
echo "================================================="
echo ""

# Verificar se Git está instalado
if ! command -v git &> /dev/null; then
    echo "❌ Git não encontrado. Instalando..."
    sudo apt install git -y
fi

# Configurar Git (se necessário)
if [ -z "$(git config --global user.name)" ]; then
    echo "📝 Configurando Git..."
    read -p "Digite seu nome: " git_name
    read -p "Digite seu email: " git_email
    git config --global user.name "$git_name"
    git config --global user.email "$git_email"
    echo "✅ Git configurado!"
fi

# Verificar se já é um repositório Git
if [ ! -d ".git" ]; then
    echo "🔧 Inicializando repositório Git..."
    git init
    echo "✅ Repositório inicializado!"
else
    echo "✅ Repositório Git já existe!"
fi

# Adicionar .gitignore se não existir
if [ ! -f ".gitignore" ]; then
    echo "📝 Criando .gitignore..."
    cat > .gitignore << 'EOF'
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.env.production
.phpunit.result.cache
Homestead.json
Homestead.yaml
auth.json
npm-debug.log
yarn-error.log
/.fleet
/.idea
/.vscode
/deploy
database_export.sql
EOF
    echo "✅ .gitignore criado!"
fi

# Adicionar todos os arquivos
echo "📦 Adicionando arquivos ao Git..."
git add .

# Fazer commit
echo "💾 Fazendo commit..."
git commit -m "Deploy para Railway - Chris Pincel Mágico" || echo "⚠️ Nada novo para commitar"

echo ""
echo "✅ Preparação concluída!"
echo ""
echo "📋 PRÓXIMOS PASSOS:"
echo ""
echo "1️⃣  Criar repositório no GitHub:"
echo "   https://github.com/new"
echo ""
echo "2️⃣  Conectar repositório:"
echo "   git remote add origin https://github.com/SEU-USUARIO/chris-pincel-magico.git"
echo ""
echo "3️⃣  Fazer push:"
echo "   git branch -M main"
echo "   git push -u origin main"
echo ""
echo "4️⃣  Deploy no Railway:"
echo "   https://railway.app → New Project → Deploy from GitHub"
echo ""
echo "📖 Consulte DEPLOY-RAILWAY.md para instruções detalhadas!"
echo ""
