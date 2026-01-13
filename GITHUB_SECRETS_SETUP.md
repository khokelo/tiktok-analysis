# 🚀 Railway Deployment Secrets Configuration

## GitHub Actions Secrets Setup

GitHub Secrets are encrypted environment variables used by CI/CD pipelines.

### How to Add Secrets

1. Go to your GitHub repository
2. Navigate to: **Settings → Secrets and variables → Actions**
3. Click **New repository secret**
4. Add each secret below

---

## Required Secrets

### 1. RAILWAY_TOKEN (CRITICAL)

**What it is:**
- Authentication token for Railway API
- Allows GitHub Actions to deploy your app
- Keep it SECRET - never commit to repo

**How to get it:**
1. Log in to https://railway.app
2. Click your **Profile (bottom left)**
3. Navigate to **Tokens**
4. Click **Create token**
5. Copy the generated token
6. **DO NOT** share or commit this token

**Add to GitHub:**
```
Name: RAILWAY_TOKEN
Value: [paste token from Railway]
```

**Example format:**
```
rail_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

---

### 2. RAILWAY_APP_URL

**What it is:**
- Your deployed application's URL
- Format: `https://app-name.railway.app`
- Used for health checks and redirects

**How to get it:**
1. After first deployment, Railway generates a URL
2. Find it in: Railway Dashboard → Project → Domain/URL
3. Or check GitHub Actions deployment output

**Add to GitHub:**
```
Name: RAILWAY_APP_URL
Value: https://your-app-name.railway.app
```

**Note:** You can leave blank on first deployment and update later after first successful deploy.

---

### 3. APP_KEY

**What it is:**
- Laravel encryption key
- Used for encrypting session data and cookies
- Must be 32 bytes encoded in base64

**How to generate it:**
```bash
# Run this in your project root
php artisan key:generate --show
```

**Output example:**
```
base64:Xxxxx+yyyyy/zzzz==
```

**Add to GitHub:**
```
Name: APP_KEY
Value: [paste output from above command]
```

**IMPORTANT:**
- Must start with `base64:`
- Must be 32 bytes
- Must be kept secret
- Generate it from your local environment

---

## Optional Secrets (Production Recommended)

### MAIL_FROM_ADDRESS
For email notifications in production

### SENTRY_DSN
For error tracking in production

---

## Verification

After adding secrets:

```bash
# In GitHub Actions logs, you should see:
✓ RAILWAY_TOKEN set
✓ RAILWAY_APP_URL set  
✓ APP_KEY set

# Secrets are never printed in logs (shows as ***)
```

---

## Important Notes

⚠️ **Security Best Practices:**

1. **Never** commit `.env` file with actual values
2. **Never** paste secrets in code
3. **Never** log or print secret values
4. **Rotate** tokens periodically (change in Railway, update in GitHub)
5. **Limit** who can access repository settings
6. **Review** who has access to your Railway project

---

## Troubleshooting

### Error: "Invalid token"
- Verify token was copied completely
- Generate new token in Railway
- Update in GitHub Secrets

### Error: "Cannot connect to Railway"
- Check RAILWAY_TOKEN is correct
- Check token hasn't expired
- Regenerate and update

### Error: "Deployment URL not found"
- Leave RAILWAY_APP_URL empty on first deploy
- After first successful deployment, update the secret
- Check Railway dashboard for actual URL

---

**Last Updated**: 2024
**Security Level**: Critical
