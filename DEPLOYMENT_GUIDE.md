# Deployment Guide: Vercel + Supabase

This guide will help you host your Restaurant Management System using **Supabase** (Database) and **Vercel** (Hosting).

## 1. Supabase Setup (Database)

1.  **Create Project**: Go to [supabase.com](https://supabase.com/), sign up, and create a new project.
2.  **Database Password**: Save the database password you set.
3.  **Run SQL Script**:
    *   In your Supabase dashboard, go to the **SQL Editor** (left sidebar).
    *   Click "New query".
    *   Copy the contents of `supabase_setup.sql` from this project and paste it there.
    *   Click **Run**.
4.  **Get Connection Details**:
    *   Go to **Project Settings** > **Database**.
    *   Under **Connection Parameters**, find your Host, DB Name, User, and Port (usually 5432).
    *   Find the **Connection String** (URI) - it looks like `postgresql://postgres:[PASSWORD]@[HOST]:5432/postgres`.

## 2. Vercel Setup (Hosting)

1.  **Push to GitHub**: If you haven't already, push your code to a GitHub repository.
2.  **Import to Vercel**:
    *   Go to [vercel.com](https://vercel.com/) and click **Add New** > **Project**.
    *   Import your GitHub repository.
3.  **Configure Project**:
    *   **Framework Preset**: Other (it will auto-detect from `vercel.json`).
    *   **Root Directory**: Leave as `./`.
4.  **Add Environment Variables**:
    *   Click on **Environment Variables** and add the following:
        *   `DB_HOST`: Your Supabase Host (e.g., `db.xxxx.supabase.co`)
        *   `DB_PORT`: `5432`
        *   `DB_USER`: `postgres`
        *   `DB_PASS`: Your Supabase Database Password
        *   `DB_NAME`: `postgres`
        *   `DB_DRIVER`: `pgsql`
        *   `URLROOT`: Your Vercel deployment URL (e.g., `https://your-project.vercel.app`)
5.  **Deploy**: Click **Deploy**.

## 3. Important Notes

*   **Vercel PHP**: We are using the `vercel-php` community runtime. It is configured in your `vercel.json`.
*   **PostgreSQL**: Supabase uses PostgreSQL. The app has been updated to support both MySQL (local) and PostgreSQL (production).
*   **Images**: If you use local images in `public/img`, make sure they are committed to GitHub.

## 4. Local Testing with Supabase

If you want to test the Supabase connection locally:
1.  Update your `app/config/config.php` (or set environment variables locally).
2.  Change `DB_DRIVER` to `pgsql`.
3.  Ensure your local PHP has the `pdo_pgsql` extension enabled in `php.ini`.
