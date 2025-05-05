"use client"

import { Button } from "@/components/ui/button"
import { Download } from "lucide-react"
import { SelectFilter } from "./SelectFilter"
import { InputFilter } from "./InputFilter"
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card"
import { SortButton } from "./SortButton"

interface FiltersProps {
    gpuTypes: string[]
    providers: string[]
    regions: string[]
    setSelectedGPU: (value: string) => void
    setSelectedProvider: (value: string) => void
    setSelectedRegion: (value: string) => void
    setMaxPrice: (value: string) => void
    exportData: () => void
}

export default function Filters({
    gpuTypes,
    providers,
    regions,
    setSelectedGPU,
    setSelectedProvider,
    setSelectedRegion,
    setMaxPrice,
    exportData,
}: FiltersProps) {
    return (
        <div className="top-0 z-10">
            <Card className="rounded-none border-0 bg-background">
                <CardContent className="p-4 space-y-8">
                    {/* 
                        1) Première “ligne” : gros filtres 
                        On limite la largeur via max-w-4xl et on centre le conteneur 
                        Mais on ne centre plus chaque colonne, on les laisse alignées à gauche 
                    */}
                    <div className="max-w-3xl mx-auto w-full">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {/* Colonne de gauche : 2 filtres empilés */}
                            <div className="flex flex-col items-center gap-4">
                                <SelectFilter
                                    placeholder="GPU Type"
                                    options={gpuTypes}
                                    onChange={setSelectedGPU}
                                    selectClassName="h-14 px-6 text-base w-96 border border-white bg-black text-white focus:ring-0 focus:border-white outline-none" />
                                <SelectFilter
                                    placeholder="Region"
                                    options={regions}
                                    onChange={setSelectedRegion}
                                    selectClassName="h-14 px-6 text-base w-96 border border-white bg-black text-white focus:ring-0 focus:border-white outline-none" />
                            </div>

                            {/* Colonne de droite : 2 filtres empilés */}
                            <div className="flex flex-col items-center gap-4 w-full md:w-96">
                                <SelectFilter
                                    placeholder="Provider"
                                    options={providers}
                                    onChange={setSelectedProvider}
                                    selectClassName="h-14 px-6 text-base w-96 border border-white bg-black text-white focus:ring-0 focus:border-white outline-none" />

                                <SelectFilter
                                    placeholder="Price"
                                    options={providers}
                                    onChange={setSelectedProvider}
                                    selectClassName="h-14 px-6 text-base w-96 border border-white bg-black text-white focus:ring-0 focus:border-white outline-none" />
                            </div>
                        </div>
                    </div>

                    <div className="max-w-3xl mx-auto w-full flex justify-between items-center">
                        <div className="flex flex-nowrap gap-4 overflow-x-auto scrollbar-none min-w-full">
                            <SelectFilter
                                placeholder="VRAM"
                                options={[]} // Vide pour le moment
                                onChange={() => { }}
                                selectClassName="h-8 w-26 px-4 text-sm border border-white bg-black text-white focus:ring-0 focus:border-white outline-none"
                            />
                            <SelectFilter
                                placeholder="vCPU"
                                options={[]} // Vide pour le moment
                                onChange={() => { }}
                                selectClassName="h-8 w-26 px-4 text-sm border border-white bg-black text-white focus:ring-0 focus:border-white outline-none"
                            />
                            <SelectFilter
                                placeholder="OS"
                                options={[]} // Vide pour le moment
                                onChange={() => { }}
                                selectClassName="h-8 w-26 px-4 text-sm border border-white bg-black text-white focus:ring-0 focus:border-white outline-none"
                            />
                            <SelectFilter
                                placeholder="Recent"
                                options={[]} // Vide pour le moment
                                onChange={() => { }}
                                selectClassName="h-8 w-26 px-4 text-sm border border-white bg-black text-white focus:ring-0 focus:border-white outline-none"
                            />
                            <Button
                                variant="default"
                                className="h-8 px-4 text-sm"
                                onClick={exportData}
                            >
                                Export
                                <Download className="ml-2 h-4 w-4" />
                            </Button>

                        </div>
                    </div>
                </CardContent >
            </Card >
        </div >
    )
}