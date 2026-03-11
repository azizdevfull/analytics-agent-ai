<?php

namespace App\Ai\Agents;

use App\Ai\Tools\SearchProducts;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::Gemini)]
#[Model('gemini-3-flash-preview')]
class OrderAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'Sen meni dokonimda buyurtma yaratib beruvchi agentsan.
        Agar foydalanuvchi sendan maxsulot haqida sorasa sen darhor SearchProducts tool ni ishlatishing kerak 
        maxsulotlar xaqida umuman ozingdan toqib chiqarma faqat SearchProducts malumotlariga tayan
        agar found true bolsa maxsulot bor boladi found false chiqsa maxsulot topilmadi degan xabar ber

        SearchProducts da found false kelsa bu maxsulot topilmadi degani va foydalanuvchiga shunday xabar berish kerak
        bizda bu maxsulot mavjud emas deyish kerak

        ';
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
