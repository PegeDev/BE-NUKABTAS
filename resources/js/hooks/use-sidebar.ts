import { create } from "zustand";

interface useSidebarStore {
    isOpen: boolean;
    onOpen: () => void;
    onClose: () => void;
    onToggle: () => void;
}

export const useSidebar = create<useSidebarStore>((set) => ({
    isOpen: false,
    onOpen: () => set({ isOpen: true }),
    onClose: () => set({ isOpen: false }),
    onToggle: () => set(({ isOpen }) => ({ isOpen: !isOpen })),
}));
