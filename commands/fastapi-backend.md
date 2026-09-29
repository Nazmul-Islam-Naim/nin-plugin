---
description: Build a FastAPI backend feature end to end (SOLID layered architecture, feature-package structure).
argument-hint: <what backend feature to build>
---

Task: $ARGUMENTS

If the task is empty, infer it from the open file or the current conversation.

Reply only in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms in English.

Run these steps in order. For each step, invoke the named skill and follow it. Do not restate its rules.

1. **Plan the layers.** Invoke `backend-architecture`: settle the route/controller → service/use-case → repository → domain-entity plan for this feature and how the five SOLID principles apply to it.
2. **Place it in FastAPI.** Invoke `fastapi-structure`: map that plan onto the project's feature-package structure — `app/<feature>/` with a router, Pydantic schemas, a service, a repository (`Protocol` + SQLAlchemy implementation), and a model, wired together with `Depends()`.
