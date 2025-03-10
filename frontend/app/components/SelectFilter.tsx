"use client"

import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from "@/components/ui/select"

interface SelectFilterProps {
    placeholder: string
    options: string[]
    onChange: (value: string) => void
    selectClassName?: string
}

export function SelectFilter({ placeholder, options, onChange, selectClassName }: SelectFilterProps) {
    return (
        <Select onValueChange={onChange}>
            <SelectTrigger className={`w-44 ${selectClassName ?? ""}`}>
                <SelectValue placeholder={placeholder} />
            </SelectTrigger>
            <SelectContent>
                {options.map((option) => (
                    <SelectItem key={option} value={option}>
                        {option}
                    </SelectItem>
                ))}
            </SelectContent>
        </Select>
    )
}
