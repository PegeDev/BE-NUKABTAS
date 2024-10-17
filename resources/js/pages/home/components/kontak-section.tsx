import { Location20Filled, Mail20Filled } from "@fluentui/react-icons";
import {
    Button,
    Field,
    Fieldset,
    Input,
    Label,
    Legend,
    Textarea,
} from "@headlessui/react";

export const KontakSection = () => {
    return (
        <section
            id="kontak"
            className="after:-z-10 after:bg-white after:h-1/2 after:w-full after:left-0 after:bottom-0 after:content-[''] after:absolute after:mt-4 relative"
        >
            <div className="container pt-32 pb-24">
                <div className="grid gap-8 md:grid-cols-5">
                    <div className="flex flex-col justify-center md:col-span-3">
                        <div className="mb-16 space-y-2">
                            <span className="text-lg font-semibold text-primary">
                                Kontak
                            </span>
                            <h2 className="font-bold leading-tight text-[32px] md:text-[42px]">
                                Hubungi Kami
                            </h2>
                        </div>
                        <div className="flex flex-wrap items-center gap-6">
                            <div className="flex items-start justify-between gap-4">
                                <div className="flex items-center justify-center w-10 h-10 bg-white rounded-lg md:bg-slate-200 text-primary">
                                    <Location20Filled />
                                </div>
                                <div className="flex flex-col gap-2">
                                    <p className="text-lg font-semibold">
                                        Lokasi Kantor
                                    </p>
                                    <span>Jl. Jendral Sudirman No. 1</span>
                                </div>
                            </div>
                            <div className="flex items-start gap-4">
                                <div className="flex items-center justify-center w-10 h-10 bg-white rounded-lg md:bg-slate-200 text-primary">
                                    <Mail20Filled />
                                </div>
                                <div className="flex flex-col gap-2">
                                    <p className="text-lg font-semibold">
                                        Kontak
                                    </p>
                                    <span>Jl. Jendral Sudirman No. 1</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Fieldset className="w-full p-4 space-y-8 bg-white rounded-lg md:px-8 md:py-4 drop-shadow-lg md:col-span-2">
                        <Legend className="mt-4 text-2xl font-bold">
                            Kirim Pesan
                        </Legend>
                        <Field>
                            <Label className="block">Nama Lengkap</Label>
                            <Input
                                className="w-full border rounded-lg border-primary"
                                placeholder="Nama Lengkap"
                                name="nama_lengkap"
                            />
                        </Field>
                        <Field>
                            <Label className="block">Email</Label>
                            <Input
                                className="w-full border rounded-lg border-primary"
                                placeholder="Email"
                                name="email"
                            />
                        </Field>
                        <Field>
                            <Label className="block">No. Telepon</Label>
                            <Input
                                className="w-full border rounded-lg border-primary"
                                placeholder="No. Telp / Whatsapp"
                                name="no_telp"
                            />
                        </Field>
                        <Field>
                            <Label className="block">Pesan</Label>
                            <Textarea
                                className="w-full border rounded-lg border-primary"
                                placeholder="Pesan"
                                name="pesan"
                            />
                        </Field>
                        <Button
                            className="p-4 font-semibold text-white rounded-lg bg-primary"
                            type="submit"
                        >
                            Kirim Pesan
                        </Button>
                    </Fieldset>
                </div>
            </div>
        </section>
    );
};
