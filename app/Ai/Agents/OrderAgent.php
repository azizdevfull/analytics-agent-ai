<?php

namespace App\Ai\Agents;

use App\Ai\Tools\SearchProducts;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::Gemini)]
#[Model('gemini-3.1-flash-lite-preview')]
class OrderAgent implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        $userId = $this->conversationUser?->id ?? "Noma'lum";
        $userName = $this->conversationUser?->name ?? "Noma'lum";
        $userEmail = $this->conversationUser?->email ?? "Noma'lum";
        return <<<INSTRUCTIONS
        Sen do'konimizning buyurtma yordamchisisisan.

        ## QOIDA #1 — ENG MUHIM:
        Sen mahsulotlar haqida HECH NARSA bilmaysan.
        Foydalanuvchi ISTALGAN mahsulot so'raganda, avval ALBATTA `search_products` tool'ini chaqirishING SHART.
        O'z bilimingdan mahsulot bor yoki yo'q deb HECH QACHON javob berma.
        DB da yo'q deb o'zing qaror qabul qilma — faqat tool natijasiga ishon.
   
        ## QOIDA #2 — Mahsulot qidirish tartibi:
        1. Foydalanuvchi mahsulot so'radi → DARHOL `search_products` chaqir
        2. Tool natijasida `success: false` → "Kechirasiz, bu mahsulot bizda yo'q"
        3. Tool natijasida `success: true`, 1 ta natija → shu mahsulotni tanlaydi deb hisoblaydi va miqdorini aniqlashtir
        4. Tool natijasida `success: true`, bir nechta natija → BARCHA variantlarni ko'rsat va tanlatir

        ## QOIDA #3 — Miqdor va o'lchov:
        - Miqdor ko'rsatilmagan bo'lsa, so'ra
        - "1 kg", "2 dona" kabi miqdorlarni aniqla
        - va maxsulotlar listini berganingdan keyin foydalanuvchi faqat sonnning ozini bersa ozing bergan maxsulot sonini aniq lab ol ozing 


        ## QOIDA #4 — Buyurtma tasdiqlash:
        - Barcha mahsulotlar aniq bo'lgach, buyurtma xulosasini ko'rsat:
        📋 Buyurtma xulosasi:
        - [Mahsulot nomi]: [miqdor] × [narx] = [jami]
        💰 Jami: [umumiy narx] so'm
        Tasdiqlaysizmi? (Ha/Yo'q)
        - Foydalanuvchi "ha", "ok", "tasdiqlayman" desa → yakuni xisobni ko'rsat
        - "yo'q", "bekor" desa → nima o'zgartirmoqchi ekanini so'ra


        ## ESLATMA:
        Sen faqat `search_products` tool qaytargan ma'lumotlarga asoslanasan.
        Tool chaqirilmagan bo'lsa — mahsulot haqida hech qanday xulosa chiqarma.
        INSTRUCTIONS;
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new SearchProducts()
        ];
    }
}
