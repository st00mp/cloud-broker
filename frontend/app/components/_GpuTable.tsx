"use client";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";

type Offer = {
    id: number;
    provider: string;
    instanceType: string;
    gpuModel: string;
    vram: number;
    vcpu: number;
    price: string;
    availabilityZone: string;
    os_supported: string;
    date: string;
};

export default function GpuTable({ data }: { data: Offer[] }) {
    return (
        <Table className="rounded-md border border-gray-800 shadow-sm">
            <TableHeader className="bg-gray-900 text-white">
                <TableRow>
                    <TableHead>Provider</TableHead>
                    <TableHead>Type</TableHead>
                    <TableHead>GPU Model</TableHead>
                    <TableHead>VRAM</TableHead>
                    <TableHead>vCPU</TableHead>
                    <TableHead>Price</TableHead>
                    <TableHead>Availability Zone</TableHead>
                    <TableHead>OS</TableHead>
                    <TableHead>Date</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                {data.map((offer) => (
                    <TableRow key={offer.id} className="hover:bg-gray-800">
                        <TableCell>{offer.provider}</TableCell>
                        <TableCell>{offer.instanceType}</TableCell>
                        <TableCell>{offer.gpuModel}</TableCell>
                        <TableCell>{offer.vram}</TableCell>
                        <TableCell>{offer.vcpu}</TableCell>
                        <TableCell>{offer.price}</TableCell>
                        <TableCell>{offer.availabilityZone}</TableCell>
                        <TableCell>{offer.os_supported}</TableCell>
                        <TableCell>{offer.date}</TableCell>
                    </TableRow>
                ))}
            </TableBody>
        </Table>
    )
}