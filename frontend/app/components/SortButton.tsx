"use client"

import { Button } from "@/components/ui/button";
import { ArrowUpDown } from "lucide-react";

interface SortButtonProps {
    label: string
    onClick: () => void
    variant?: string
    className?: string
}

export function SortButton({ label, onClick, variant, className }: SortButtonProps) {
    return (
        <Button
            variant={variant as any || "ghost"}
            className={className}
            onClick={onClick}>
            {label}
            <ArrowUpDown className="ml-2 h-4 w-4" />
        </Button>
    )
}