---
description: Build a Laravel backend feature end to end (SOLID layered architecture, L5 modular structure).
argument-hint: <what backend feature to build>
---

Task: $ARGUMENTS

If the task is empty, infer it from the open file or the current conversation.

Reply only in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms in English.

Run these steps in order. For each step, invoke the named skill and follow it. Do not restate its rules.

1. **Plan the layers.** Invoke `backend-architecture`: settle the route/controller → service/use-case → repository → domain-entity plan for this feature and how the five SOLID principles apply to it.
2. **Place it in Laravel.** Invoke `laravel-structure`: map that plan onto the project's actual Laravel structure — `Modules/<Name>/` (L5 modular pattern) if present or wanted, Controllers, Form Requests, Service/Action classes, Repository interfaces bound in a Service Provider, and Eloquent Models.
3. **Secure it, if needed.** When this feature involves login, registration or restricting access by role, invoke `auth-and-access`.
