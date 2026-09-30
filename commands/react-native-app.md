---
description: Design and build a React Native app or screen end to end (direction, layered structure, UI build).
argument-hint: <what mobile screen/feature to build>
---

Task: $ARGUMENTS

If the task is empty, infer it from the open file or the current conversation.

Reply only in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms in English.

Run these steps in order. For each step, invoke the named skill and follow it. Do not restate its rules.

1. **Direction.** Invoke `business-design`: infer the business, make 2-3 directions with samples, show the Bangla card. **Stop and wait for the user's pick. Write no project code before it.** Skip this step when the app's visual direction is already picked and this is just a new feature inside it.
2. **Structure.** Invoke `mobile-architecture`, then `react-native-structure`: settle the screen/state/repository/API-client plan and place files in the project's feature-based structure — navigation, state layer, API client and screens.
3. **Build.** Invoke `react-native-ui` to build the screens and components in the chosen direction — tokens, states, safe-area/responsive layout, accessibility and polish. Add `mock-data-layer` when the backend endpoint isn't ready yet, and `auth-and-access` when the screen needs login or protected content.
