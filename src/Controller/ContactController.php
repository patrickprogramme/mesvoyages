<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


/**
 * Description of ContactController
 *
 * @author Patrick
 */
class ContactController extends AbstractController {
    
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
    
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer) : Response {
        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);
        
        if ($formContact->isSubmitted() && $formContact->isValid())
        {
            $this->sendEmail($mailer, $contact);
            $this->addFlash('success', 'Votre message a bien été envoyé !');
            return $this->redirectToRoute('contact');
        }
        return $this->render("pages/contact.html.twig", [
            'contact' => $contact,
            'formcontact' => $formContact->createView()
        ]);
    }
    
    public function sendEmail(MailerInterface $mailer, Contact $contact)
    {
        $destination = $this->params->get('contact_email');
        
        $email = (new Email())
            ->from($contact->getEmail())
            ->to($destination)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Message du site de voyages')
            //->text('Sending emails is fun again!')
            ->html($this->renderView(
                    'pages/_email.html.twig', [
                        'contact' => $contact
                    ]
            ),'utf-8'
                    );

        $mailer->send($email);
    }
}
