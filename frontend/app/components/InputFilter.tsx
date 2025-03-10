"use client"

import { Input } from "@/components/ui/input"

interface InputFilterProps {
    placeholder: string
    onChange: (value: string) => void
    inputClassName?: string
}

export function InputFilter({ placeholder, onChange, inputClassName
}: InputFilterProps) {
    return (
        <Input
            type="number"
            placeholder={placeholder}
            onChange={(e) => onChange(e.target.value)}
            className={`w-44 ${inputClassName ?? ""}`}
        />
    )
}
