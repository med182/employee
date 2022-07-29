<?php

namespace App\Test\Controller;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployeeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EmployeeRepository $repository;
    private string $path = '/employee/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Employee::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Employee index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'employee[firstname]' => 'Testing',
            'employee[lastname]' => 'Testing',
            'employee[phoneNumber]' => 'Testing',
            'employee[email]' => 'Testing',
            'employee[address]' => 'Testing',
            'employee[position]' => 'Testing',
            'employee[salary]' => 'Testing',
            'employee[birthDate]' => 'Testing',
        ]);

        self::assertResponseRedirects('/employee/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Employee();
        $fixture->setFirstname('My Title');
        $fixture->setLastname('My Title');
        $fixture->setPhoneNumber('My Title');
        $fixture->setEmail('My Title');
        $fixture->setAddress('My Title');
        $fixture->setPosition('My Title');
        $fixture->setSalary('My Title');
        $fixture->setBirthDate('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Employee');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Employee();
        $fixture->setFirstname('My Title');
        $fixture->setLastname('My Title');
        $fixture->setPhoneNumber('My Title');
        $fixture->setEmail('My Title');
        $fixture->setAddress('My Title');
        $fixture->setPosition('My Title');
        $fixture->setSalary('My Title');
        $fixture->setBirthDate('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'employee[firstname]' => 'Something New',
            'employee[lastname]' => 'Something New',
            'employee[phoneNumber]' => 'Something New',
            'employee[email]' => 'Something New',
            'employee[address]' => 'Something New',
            'employee[position]' => 'Something New',
            'employee[salary]' => 'Something New',
            'employee[birthDate]' => 'Something New',
        ]);

        self::assertResponseRedirects('/employee/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getFirstname());
        self::assertSame('Something New', $fixture[0]->getLastname());
        self::assertSame('Something New', $fixture[0]->getPhoneNumber());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getAddress());
        self::assertSame('Something New', $fixture[0]->getPosition());
        self::assertSame('Something New', $fixture[0]->getSalary());
        self::assertSame('Something New', $fixture[0]->getBirthDate());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Employee();
        $fixture->setFirstname('My Title');
        $fixture->setLastname('My Title');
        $fixture->setPhoneNumber('My Title');
        $fixture->setEmail('My Title');
        $fixture->setAddress('My Title');
        $fixture->setPosition('My Title');
        $fixture->setSalary('My Title');
        $fixture->setBirthDate('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/employee/');
    }
}
