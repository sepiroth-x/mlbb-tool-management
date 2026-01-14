# AI-Powered Matchup Analysis - Setup Guide

## Overview
The Analyze Match-up feature has been enhanced with **AI-powered analysis** using OpenAI's ChatGPT API. This provides intelligent, context-aware strategic insights beyond basic rule-based calculations.

## What Was Implemented

### 1. **OpenAI Service Integration**
- Created `OpenAIService.php` that connects to ChatGPT API
- Uses your personal OpenAI API key for analysis
- Supports GPT-3.5-turbo (free/affordable) and GPT-4 (premium)
- Handles API communication, error handling, and response parsing

### 2. **Enhanced Analysis**
The system now provides:
- **Basic Rule-Based Analysis** (always available)
  - Win probability calculations
  - Hero counter analysis
  - Team composition strengths/weaknesses
  - Game phase breakdown

- **AI-Powered Insights** (when API key is configured)
  - 🤖 Key matchup insights (3 critical observations)
  - 📋 Team A specific strategy recommendations
  - 📋 Team B specific strategy recommendations
  - ⏰ Phase advantage analysis with reasoning

### 3. **Frontend Updates**
- AI badge indicator when AI analysis is active
- Beautiful gradient-styled AI insights section
- Maintains all original features alongside AI enhancements

## Setup Instructions

### Step 1: Get Your OpenAI API Key

1. **Create/Login to OpenAI Account**
   - Go to: https://platform.openai.com/
   - Sign up or log in with your existing ChatGPT account

2. **Generate API Key**
   - Navigate to: https://platform.openai.com/api-keys
   - Click "Create new secret key"
   - Give it a name (e.g., "MLBB Matchup Analyzer")
   - **IMPORTANT**: Copy the key immediately (you won't see it again!)
   - Format: `sk-proj-...` (starts with sk-proj or sk-)

3. **Add Credits (If Needed)**
   - OpenAI API uses pay-as-you-go pricing
   - GPT-3.5-turbo: ~$0.0015 per analysis (very cheap!)
   - GPT-4: ~$0.03 per analysis (more expensive but better)
   - Add billing: https://platform.openai.com/account/billing

### Step 2: Configure Your Application

1. **Copy Environment Configuration**
   ```bash
   # If you don't have .env file yet
   cp .env.example .env
   ```

2. **Edit .env File**
   Open `.env` file and find the OpenAI section (near the bottom):
   ```env
   # OpenAI Configuration (for AI-powered matchup analysis)
   OPENAI_API_KEY=sk-proj-YOUR_ACTUAL_API_KEY_HERE
   OPENAI_MODEL=gpt-3.5-turbo
   OPENAI_MAX_TOKENS=500
   OPENAI_TEMPERATURE=0.7
   ```

3. **Replace with Your Key**
   ```env
   OPENAI_API_KEY=sk-proj-abc123...xyz789
   ```

### Step 3: Configuration Options

#### Model Selection
```env
# Budget-friendly (recommended for testing)
OPENAI_MODEL=gpt-3.5-turbo

# Premium quality (better analysis, costs more)
OPENAI_MODEL=gpt-4o-mini

# Best quality (most expensive)
OPENAI_MODEL=gpt-4
```

#### Response Length
```env
# Concise responses (500 tokens = ~$0.0015 per request)
OPENAI_MAX_TOKENS=500

# Detailed responses (1000 tokens = ~$0.003 per request)
OPENAI_MAX_TOKENS=1000
```

#### Creativity Level
```env
# More factual/consistent (0.3-0.5)
OPENAI_TEMPERATURE=0.3

# Balanced (0.7)
OPENAI_TEMPERATURE=0.7

# More creative/varied (0.9-1.0)
OPENAI_TEMPERATURE=0.9
```

### Step 4: Clear Cache (Important!)
After modifying `.env`, clear your application cache:

```bash
php artisan config:clear
php artisan cache:clear
```

Or use the admin panel cache clear function.

### Step 5: Test the Feature

1. Navigate to: `http://your-site.com/mlbb/matchup`
2. Select 5 heroes for Team A
3. Select 5 heroes for Team B
4. Click "⚡ Analyze Matchup"
5. If configured correctly, you'll see:
   - ✨ "AI-Powered Analysis" badge
   - 🤖 AI Strategic Analysis section with insights

## How It Works

### Analysis Flow
```
1. User selects teams → 
2. Basic rule-based analysis runs (always) →
3. If OpenAI API key configured →
4. Send team data + basic analysis to ChatGPT →
5. Receive AI strategic insights →
6. Display combined analysis to user
```

### API Usage
- Each analysis = 1 API call
- Typical cost with GPT-3.5-turbo: $0.0015 - $0.003 per analysis
- With 1000 analyses: ~$1.50 - $3.00
- Very affordable for personal/small team use!

### Fallback Behavior
- If API key not configured: Works without AI (rule-based only)
- If API call fails: Shows basic analysis, logs error
- If API quota exceeded: Gracefully falls back to basic analysis

## Troubleshooting

### Issue: No AI Insights Showing

**Check 1: API Key Configuration**
```bash
php artisan tinker
>>> config('services.openai.api_key')
# Should show your key, not null
```

**Check 2: Cache Cleared**
```bash
php artisan config:clear
```

**Check 3: API Key Valid**
- Test at: https://platform.openai.com/playground
- Check billing: https://platform.openai.com/account/billing

### Issue: API Errors in Logs

**Check Laravel Logs**
```bash
tail -f storage/logs/laravel.log
```

Common errors:
- `401 Unauthorized`: Invalid API key
- `429 Too Many Requests`: Rate limit exceeded
- `insufficient_quota`: No credits/billing not set up

### Issue: Slow Response Time

**Solutions:**
- Use GPT-3.5-turbo (faster than GPT-4)
- Reduce `OPENAI_MAX_TOKENS` to 300-400
- Consider caching results for identical team compositions

## Cost Estimates

### GPT-3.5-Turbo (Recommended)
- Input: ~$0.0005 per analysis
- Output: ~$0.0015 per analysis
- **Total: ~$0.002 per matchup**
- 500 analyses = ~$1.00

### GPT-4o-Mini (Balanced)
- Input: ~$0.015 per analysis
- Output: ~$0.06 per analysis
- **Total: ~$0.075 per matchup**
- 100 analyses = ~$7.50

### GPT-4 (Premium)
- Input: ~$0.03 per analysis
- Output: ~$0.06 per analysis
- **Total: ~$0.09 per matchup**
- 100 analyses = ~$9.00

## Security Best Practices

1. **Never commit .env file to git**
   - Already in `.gitignore`
   - API key should remain private

2. **Restrict API Key**
   - In OpenAI dashboard, set usage limits
   - Monitor usage regularly

3. **Production Considerations**
   - Consider implementing request rate limiting
   - Cache frequent team compositions
   - Set monthly spending limits in OpenAI

## Advanced Features (Future)

The OpenAI service includes a bonus method ready for future implementation:
- `suggestCounterPicks()`: AI-powered hero counter suggestions during draft

## Support

### OpenAI Resources
- API Documentation: https://platform.openai.com/docs
- Pricing: https://openai.com/pricing
- Usage Dashboard: https://platform.openai.com/usage

### Questions?
- Check Laravel logs: `storage/logs/laravel.log`
- Test API key at OpenAI Playground
- Verify `.env` configuration loaded correctly

## Summary

✅ **Implemented:**
- OpenAI ChatGPT integration
- AI-powered strategic analysis
- Fallback to rule-based analysis
- Beautiful UI for AI insights
- Configurable model/parameters

✅ **Configuration:**
- Add your OpenAI API key to `.env`
- Choose model (gpt-3.5-turbo recommended)
- Clear cache
- Test the feature

✅ **Cost-Effective:**
- ~$0.002 per analysis with GPT-3.5-turbo
- Only charged when AI analysis used
- Falls back to free basic analysis if API unavailable

🎉 **Ready to Use!**
Your Analyze Match-up feature now has AI-powered insights using your own ChatGPT account credentials!
