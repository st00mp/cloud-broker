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
            <SelectTrigger className={`w-44 border !border-white ring-0 focus:ring-0 focus:outline-none focus:border-white gap-2 ${selectClassName ?? ""}`}>
                <SelectValue placeholder={placeholder} />
            </SelectTrigger>
            <SelectContent className="bg-black text-white border border-white">
                {options.map((option) => (
                    <SelectItem key={option} value={option}>
                        {option}
                    </SelectItem>
                ))}
            </SelectContent>
        </Select>
    )
}
