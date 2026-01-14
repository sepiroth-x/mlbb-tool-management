# 🎯 Quick Start: AI-Powered Matchup Analysis

## ⚡ 3-Step Setup

### 1️⃣ Get OpenAI API Key
→ Visit: https://platform.openai.com/api-keys
→ Create new key
→ Copy it (starts with `sk-proj-...`)

### 2️⃣ Add to .env File
```env
OPENAI_API_KEY=sk-proj-YOUR_KEY_HERE
OPENAI_MODEL=gpt-3.5-turbo
```

### 3️⃣ Clear Cache
```bash
php artisan config:clear
```

## ✨ Features

### Without AI (Free)
✅ Win probability calculation
✅ Hero counter analysis  
✅ Team composition analysis
✅ Game phase breakdown

### With AI (Your ChatGPT Account)
✅ All basic features +
🤖 Key strategic insights
📋 Team-specific tactics
⏰ Phase advantage reasoning
💡 Context-aware recommendations

## 💰 Cost

**GPT-3.5-Turbo** (Recommended)
- ~$0.002 per analysis
- 500 analyses = $1.00
- Perfect for personal/team use

**GPT-4** (Premium)
- ~$0.09 per analysis
- Better quality insights
- More expensive

## 🔍 Testing

1. Go to: `/mlbb/matchup`
2. Select 5 heroes per team
3. Click "Analyze Matchup"
4. Look for: ✨ AI-Powered Analysis badge

## ❌ Troubleshooting

**No AI insights?**
```bash
# Check config loaded
php artisan config:clear

# Verify key in tinker
php artisan tinker
>>> config('services.openai.api_key')
```

**API Errors?**
- Check logs: `storage/logs/laravel.log`
- Verify billing: https://platform.openai.com/account/billing
- Test key: https://platform.openai.com/playground

## 📚 Full Guide
See: `AI_MATCHUP_SETUP_GUIDE.md`

## 🎉 That's It!
Your matchup analyzer now uses YOUR ChatGPT account for intelligent analysis!
