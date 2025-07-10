# UI Components

This directory contains reusable UI components used across the frontend.
Most elements are based on [shadcn/ui](https://ui.shadcn.com) and are
located in the `ui` subfolder.

## Available components

- **Button** (`ui/button.tsx`)
- **Card** (`ui/card.tsx`)
- **DropdownMenu** (`ui/dropdown-menu.tsx`)
- **Input** (`ui/input.tsx`)
- **Label** (`ui/label.tsx`)
- **NavigationMenu** (`ui/navigation-menu.tsx`)
- **Select** (`ui/select.tsx`)
- **Table** (`ui/table.tsx`)

Additional page-specific components live in `app/components`.

## Storybook

A Storybook configuration can be added to document and test components
interactively. After installing Storybook, run:

```bash
npm run storybook
```

to open the component explorer.
